<?php 
/**
 *  Copyright (c) 2011, Лицман Дмитрий
 *
 * Разрешается повторное распространение и использование как в виде исходного кода, так и в
 * двоичной форме, с изменениями или без, при соблюдении следующих условий:
 *
 *     * При повторном распространении исходного кода должно оставаться указанное выше
 *       уведомление об авторском праве, этот список условий и последующий отказ от гарантий.
 *     * При повторном распространении двоичного кода должна сохраняться указанная выше
 *       информация об авторском праве, этот список условий и последующий отказ от гарантий в
 *       документации и/или в других материалах, поставляемых при распространении. 
 *     * Ни название <Организации>, ни имена ее сотрудников не могут быть использованы в
 *       качестве поддержки или продвижения продуктов, основанных на этом ПО без
 *       предварительного письменного разрешения. 
 *
 * ЭТА ПРОГРАММА ПРЕДОСТАВЛЕНА ВЛАДЕЛЬЦАМИ АВТОРСКИХ ПРАВ И/ИЛИ ДРУГИМИ СТОРОНАМИ
 * "КАК ОНА ЕСТЬ" БЕЗ КАКОГО-ЛИБО ВИДА ГАРАНТИЙ, ВЫРАЖЕННЫХ ЯВНО ИЛИ ПОДРАЗУМЕВАЕМЫХ,
 * ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ ИМИ, ПОДРАЗУМЕВАЕМЫЕ ГАРАНТИИ КОММЕРЧЕСКОЙ ЦЕННОСТИ И
 * ПРИГОДНОСТИ ДЛЯ КОНКРЕТНОЙ ЦЕЛИ. НИ В КОЕМ СЛУЧАЕ, ЕСЛИ НЕ ТРЕБУЕТСЯ СООТВЕТСТВУЮЩИМ
 * ЗАКОНОМ, ИЛИ НЕ УСТАНОВЛЕНО В УСТНОЙ ФОРМЕ, НИ ОДИН ВЛАДЕЛЕЦ АВТОРСКИХ ПРАВ И НИ ОДНО
 * ДРУГОЕ ЛИЦО, КОТОРОЕ МОЖЕТ ИЗМЕНЯТЬ И/ИЛИ ПОВТОРНО РАСПРОСТРАНЯТЬ ПРОГРАММУ, КАК БЫЛО
 * СКАЗАНО ВЫШЕ, НЕ НЕСЁТ ОТВЕТСТВЕННОСТИ, ВКЛЮЧАЯ ЛЮБЫЕ ОБЩИЕ, СЛУЧАЙНЫЕ,
 * СПЕЦИАЛЬНЫЕ ИЛИ ПОСЛЕДОВАВШИЕ УБЫТКИ, ВСЛЕДСТВИЕ ИСПОЛЬЗОВАНИЯ ИЛИ НЕВОЗМОЖНОСТИ
 * ИСПОЛЬЗОВАНИЯ ПРОГРАММЫ (ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ ПОТЕРЕЙ ДАННЫХ, ИЛИ ДАННЫМИ,
 * СТАВШИМИ НЕПРАВИЛЬНЫМИ, ИЛИ ПОТЕРЯМИ ПРИНЕСЕННЫМИ ИЗ-ЗА ВАС ИЛИ ТРЕТЬИХ ЛИЦ, ИЛИ ОТКАЗОМ
 * ПРОГРАММЫ РАБОТАТЬ СОВМЕСТНО С ДРУГИМИ ПРОГРАММАМИ), ДАЖЕ ЕСЛИ ТАКОЙ ВЛАДЕЛЕЦ ИЛИ
 * ДРУГОЕ ЛИЦО БЫЛИ ИЗВЕЩЕНЫ О ВОЗМОЖНОСТИ ТАКИХ УБЫТКОВ.
 * 
 * 
 * 
 * Определения официального курса валют относительно рубля
 * 
 * Курсы беруться с официального сайта ЦБ РФ.
 * Поддерживается кэш. Есть возможность использовать как системный кэш, так
 * и внутренний на основе CFileCache или другого класса
 * 
 * Использование
 * 
 * Для установки необходимо прописать в списке компонентов в конфигурационном файле
 *	'import'=>array(
 *		'application.models.*',
 *		'application.components.*',
 *		'application.extensions.cbrf.*', // папка с классом
 *	),
 *	
 * Далее добавить как компонент
 *	'components'=>array(
 *		'cbrf' => array(
 *			'class' => 'Cbrf',
 *			'defaultCurrency' => 'EUR',
 *			// дополнительные параметры смотреть в phpdoc формате класса
 *		),
 *		
 * Внутри приложения можно использовать в виде
 * 
 *	Yii::app()->cbrf->getValue(1000, 'USD')
 * вернет стоимость 1000 долларов в рублях
 *	Yii::app()->cbrf->getValue(1000)
 * вернет стоимость 1000 евро в рублях
 *	Yii::app()->cbrf->getRate('USD')
 * вернет курс доллара по отношению к рублю
 *	Yii::app()->cbrf->getRates()
 * вернет с массивом курсов
 * 
 * @todo Протестировать
 * @todo Написать readme!
 */
class Cbrf extends CApplicationComponent
{
	const DEFAULT_SOURCE_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';
	const DATA_CACHE_ID = 'cbrf_rates';
	
	/**
	 * Default currency.
	 *
	 * @var string
	 */
	public $defaultCurrency = 'USD';
	/**
	 * Rates source url.
	 *
	 * @var string
	 * @see self::DEFAULT_SOURCE_URL
	 */
	public $sourceUrl = self::DEFAULT_SOURCE_URL;
	/**
	 * Yii application component id.
	 * Defaults use yii application "cache" component.
	 * If no ICache component exists, return dummy cache.
	 *
	 * @var string|null
	 */
	public $cacheId;

	private $rates = array();
	private $cache;
	
	/**
	 * Получить стоимость валюты относительно 1 рубля.
	 *
	 * @param string $currency
	 * @throws CbrfCurrencyException При попытке передать неизвестную валюту.
	 * @return float
	 */
	public function getRate($currency)
	{
		if (isset($this->rates[$currency]))
			return $this->rates[$currency];
		else
			throw new CbrfCurrencyException('Uknown currency ' . $currency);
	}
	/**
	 * Получить значение в нужной валюте относительно рубля.
	 * 
	 * @param float $value Число в валюте
	 * @param string $currency Буквенный код валюты. По умолчанию берется дефолтная валюта из настроек.
	 * @throws CbrfCurrencyException При попытке передать неизвестную валюту.
	 * @return float
	 */
	public function getValue($value, $currency = null)
	{
		if (!$currency) $currency = $this->defaultCurrency;
		return $value * $this->getRate($currency);
	}
	
	/**
	 * Получить список всех котировок в формате [currencyCode] => (float)value
	 * 
	 * @return array
	 */
	public function getRates()
	{
		return $this->rates;
	}

	public function init()
	{
		$this->rates = $this->getCache()->get(self::DATA_CACHE_ID);
		if(!$this->rates)
		{
			$this->rates = $this->loadRatesFromService();
			$this->getCache()->set(self::DATA_CACHE_ID, $this->rates, $this->getExpireTime());
		}
	}

	/**
	 * Return expire cache time to next day.
	 * 
	 * @return int
	 */
	protected function getExpireTime()
	{
		return strtotime("tomorrow") - time();
	}

	/**
	 * Return cache object.
	 *
	 *
	 * @return ICache
	 */
	protected function getCache()
	{
		if(!$this->cache)
		{
			if($this->cacheId)
				$this->cache = Yii::app()->getComponent($this->cacheId);
			if(!($this->cache instanceof ICache))
				$this->cache = new CDummyCache;
		}
		return $this->cache;
	}

	/**
	 * Возвращает данные по валютам с сервиса от источника
	 *
	 * @throws CbrfServiceException on error load rated
	 * @return array вида CharCode => value.
	 */
	protected function loadRatesFromService()
	{
		// load and check xml doc
		$xml = $this->loadXml();
		if(!$xml || !$xml->{'Valute'})
			throw new CbrfServiceException('Invalid data from service');
		// calculate rates
		$rates = array();
		foreach($xml->{'Valute'} as $rate)
		{
			$value = str_replace(',', '.', $rate->{'Value'}) / $rate->{'Nominal'};
			$rates[current($rate->{'CharCode'})] = $value;
		}
		return $rates;
	}

	/**
	 * @throws CbrfServiceException
	 * @return SimpleXMLElement
	 */
	protected function loadXml()
	{
		// load xml doc with internal errors
		libxml_use_internal_errors(true);
    libxml_clear_errors();
//		$xml = simplexml_load_file($this->sourceUrl);
    $content = Helper::curl_get($this->sourceUrl);

    $config = array(
      'input-encoding' => 3,
      'output-encoding' => 3,
      'language' => 'ru',
      'indent'     => true,
      'input-xml'  => true,
      'output-xml' => true,
      'wrap'       => false,
    );
    // Tidy
    $tidy = new tidy;
    $tidy->parseString($content, $config);
    $tidy->cleanRepair();
    $content = ''.$tidy;

    file_put_contents('/home/webuser/cbrf.xml', $content);

		$xml = simplexml_load_string( $content );
		$errors = libxml_get_errors();
    libxml_clear_errors();
		libxml_use_internal_errors(false);
		// check for errors
		if($errors) {
      echo($content);
      throw new CbrfServiceException('Invalid xml source: '.print_r($errors, true).$content);
    }
		return $xml;
	}
}

class CbrfException extends Exception {}
class CbrfCurrencyException extends CbrfException {}
class CbrfServiceException extends CbrfException {}