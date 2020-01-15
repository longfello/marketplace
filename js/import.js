$(document).ready(function(){
  $('#start_test').on('click', function(){
    $('#preamb').hide();
    $('#import_log_wrapper').removeClass('hidden');

    importer.check_steps = $(this).data('actions');
    importer.start();
  });
});

var importer = {
  baseURL:'',
  errorCount: 0,
  current_step: 0,
  check_steps : [],
  tid:false,
  intervalTid:false,
  start: function(){
    importer.baseURL = $('#import_log_wrapper').data('url');
    importer.do_next();
  },
  setPercent: function(percent) {$('.progress-bar-import').css('width', percent+'%').html(percent+'%');},
  setPercent2: function(percent) {$('.progress-bar-import2').css('width', percent+'%').html(percent+'%');},
  do_next: function(title){
    if (importer.current_step>=importer.check_steps.length) {
      importer.current_step++;
      if (importer.errorCount > 0) {
        importer.error('Завершено с ошибками ('+importer.errorCount+')');
      } else {
        importer.log('Завершено');
      }
      importer.setPercent(100);
      importer.setPercent2(100);
    } else {
      var step  = importer.check_steps[importer.current_step];
      importer.current_step++;
      if (!title) {
        importer.log('<h3>' + step.title + '</h3>');
      } else {
        importer.log(title);
      }
      $.ajax({
        url: '',
        dataType: 'json',
        data: {action:step.action},
        type:'get',
        success: function(data){
          importer.process(data);
        },
        error: function() { importer.error("Ошибка выполнения запроса. Обратитесь к администратору."); }
      });
    }
  },
  process: function(data){
    switch (data.status) {
      case 'ok':
        importer.setPercent(Math.floor(100*(importer.current_step)/importer.check_steps.length));
        importer.setPercent2(100);
        // importer.log('Завешено: ' + step.title);
        importer.echo(data);
        importer.do_next();
        break;
      case 'error':
        importer.echo(data);
        break;
      case 'wait':
        importer.waitFor(data.for);
        break;
      case 'load':
        importer.echo(data);
        $('#import_log_wrapper .progress').hide();
        $('#import_log_wrapper #import_log').load(data.url);
        break;
      case 'redirect':
        importer.echo(data);
        document.location.href = data.url;
        break;
      default:
        console.log('Some error founded, try again...');
        importer.current_step--;
        importer.do_next('Повторная отправка задания...');
    }
  },
  waitFor: function(tid){
    importer.tid = tid;
    importer.waitIteration();
  },
  waitIteration: function(){
    $.ajax({
      url: '',
      dataType: 'json',
      data: {action:'wait', 'for':importer.tid},
      type:'get',
      success: function(data){
        var p = data.percents;
        importer.setPercent(Math.floor(100*(importer.current_step - 1)/importer.check_steps.length + p/importer.check_steps.length));
        importer.setPercent2(Math.floor(p));
        if (data.done && data.data) {
          importer.process(data.data);
        } else {
          setTimeout(importer.waitIteration, 1000);
        }
      },
      error: function() { importer.error("Ошибка выполнения запроса. Обратитесь к администратору."); }
    });

  },
  echo: function(data) {
    if (data.result && data.result.errors) {
      for(var i in data.result.errors) {
        var txt = '<h3>'+data.result.errors[i].message+'</h3>';
        for(var n in data.result.errors[i].additionalInfo) {
          for (var k in data.result.errors[i].additionalInfo[n]) {
            txt += k + ": " + data.result.errors[i].additionalInfo[n][k] + "<br>";
          }
          txt += '<br>';
        }
        importer.error(txt);
      }
    }
    if (data.errors) {
      for(var i in data.errors) {
        var txt = '<h3>'+data.errors[i].message+'</h3>';
        for(var n in data.errors[i].additionalInfo) {
          for (var k in data.errors[i].additionalInfo[n]) {
            txt += k + ": " + data.errors[i].additionalInfo[n][k] + "<br>";
          }
          txt += '<br>';
        }
        importer.error(txt);
      }
    }
    if (data.result && data.result.messages) {
      for(var i in data.result.messages) {
        importer.log(data.result.messages[i].message);
      }
    }
  },
  log: function(text, append){
    var itemid = 'log_item-'+importer.current_step;
    if ($('.'+itemid).size()) {
      $('#import_log .'+itemid).append('<br>'+text);
    } else {
      $('#import_log').append($('<div class="log_item '+itemid+' alert alert-info"></div>').html(text));
    }
  },
  error: function(text){
    var itemid = 'log_item-'+importer.current_step;
    $('#import_log').append($('<div class="log_error '+itemid+'alert alert-danger padding-all"></div>').html(text));
    importer.errorCount++;
  }
}


