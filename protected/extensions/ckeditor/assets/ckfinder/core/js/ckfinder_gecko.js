/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2009, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is NOT part of the Source Code of CKFinder.
 */

var cl = {
  gn: {
    eh: {}
  }
};
var ct = {
  Dir: CKFLang.Dir
};
var cD = false;
var aQ = {
  v: [],
  bz: function (A) {
    var B = this.v.length;
    this.v[B] = A;
    return B;
  },
  au: function (A) {
    var e = this.v[A];
    this.v[A] = null;
    return e;
  },
  cp: function () {
    var i = 0;
    while (i < this.v.length) this.v[i++] = null;
    this.v.length = 0;
  }
};
var qE = '\145';
String.prototype.cg = function (A) {
  return (this.indexOf(A) > -1);
};
String.prototype.qq = function () {
  var A = arguments;
  if (A.length == 1 && A[0].pop) A = A[0];
  for (var i = 0; i < A.length; i++) {
    if (this == A[i]) return true;
  };
  return false;
};
String.prototype.hp = function () {
  var A = this.toUpperCase();
  var B = arguments;
  if (B.length == 1 && B[0].pop) B = B[0];
  for (var i = 0; i < B.length; i++) {
    if (A == B[i].toUpperCase()) return true;
  };
  return false;
};
String.prototype.pe = function (A, B) {
  var C = this;
  for (var i = 0; i < A.length; i++) {
    C = C.replace(A[i], B[i]);
  };
  return C;
};
String.prototype.oV = function (A) {
  return (this.substr(0, A.length) == A);
};
String.prototype.eB = function (A, B) {
  var C = this.length;
  var D = A.length;
  if (D > C) return false;
  if (B) {
    var E = new RegExp(A + '$', 'i');
    return E.test(this);
  } else return (D == 0 || this.substr(C - D, D) == A);
};
String.prototype.dW = function (A, B) {
  var s = '';
  if (A > 0) s = this.substring(0, A);
  if (A + B < this.length) s += this.substring(A + B, this.length);
  return s;
};
String.prototype.fO = function () {
  return this.replace(/(^[ \t\n\r]*)|([ \t\n\r]*$)/g, '');
};
String.prototype.qy = function () {
  return this.replace(/^[ \t\n\r]*/g, '');
};
String.prototype.qz = function () {
  return this.replace(/[ \t\n\r]*$/g, '');
};
String.prototype.hl = function (A) {
  return this.replace(/\n/g, A);
};
String.prototype.hk = function (A, B, C) {
  if (typeof B == 'function') {
    return this.replace(A, function () {
      return B.apply(C || this, arguments);
    });
  } else return this.replace(A, B);
};
Array.prototype.Q = function (A) {
  var i = this.length;
  this[i] = A;
  return i;
};
Array.prototype.qc = function (A) {
  for (var i = 0; i < this.length; i++) {
    if (this[i] == A) return i;
  };
  return -1;
};
var fX = 'images/';
var at = 'images/spacer.gif';
var qD = 1000;
var qt = 2000;
var qK = 4000;
var gY = 0;
var gO = 1;
var gV = 2;
var s = navigator.userAgent.toLowerCase();
var U = {
  dF: /*@cc_on!@*/ false,
  pm: /*@cc_on!@*/ false && (parseFloat(s.match(/msie ([\d|\.]+)/)[1]) >= 7),
  dR: s.cg('gecko/'),
  eA: s.cg(' applewebkit/'),
  gu: !! window.opera,
  qx: s.cg('macintosh')
};
(function (A) {
  A.oB = (A.dR || A.eA || A.gu);
  if (A.dR) {
    var B = s.match(/gecko\/(\d+)/)[1];
    A.hE = ((B < 20051111) || (/rv:1\.7/.test(s)));
    A.hj = /rv:1\.9/.test(s);
  } else A.hE = false;
})(U);
var cH = cl.qn = {};
if (document.location.protocol == 'file:') {
  cH.aD = decodeURIComponent(document.location.pathname.substr(1));
  cH.aD = cH.aD.replace(/\\/gi, '/');
  var ff = document.location.href.match(/^(file\:\/{2,3})/)[1];
  if (U.gu) ff += 'localhost/';
  cH.aD = ff + cH.aD.substring(0, cH.aD.lastIndexOf('/') + 1);
  cH.fQ = cH.aD;
} else {
  cH.aD = document.location.pathname.substring(0, document.location.pathname.lastIndexOf('/') + 1);
  cH.fQ = document.location.protocol + '//' + document.location.host + cH.aD;
};
cH.oS = cH.aD.replace(/editor\/$/, '');
try {
  cH.gc = screen.width;
  cH.fD = screen.height;
} catch (e) {
  cH.gc = 800;
  cH.fD = 600;
};
cH.hK = function () {
  this.bL = {};
  var A = window.parent.document.getElementById(cl.Name + '___Config');
  if (!A) return;
  var B = A.value.split('&');
  for (var i = 0; i < B.length; i++) {
    if (B[i].length == 0) continue;
    var C = B[i].split('=');
    var D = decodeURIComponent(C[0]);
    var E = decodeURIComponent(C[1]);
    if (D == 'CustomConfigurationsPath') cH[D] = E;
    else if (E.toLowerCase() == "true") this.bL[D] = true;
    else if (E.toLowerCase() == "false") this.bL[D] = false;
    else if (E.length > 0 && !isNaN(E)) this.bL[D] = parseInt(E, 10);
    else this.bL[D] = E;
  }
};

function fW() {
  var A = cH.bL;
  for (var B in A) cH[B] = A[B];
};

function gH() {
  var A = cH;
  if (A.gq) {
    try {
      if ((/fckdebug=true/i).test(window.top.location.search)) A.kb = true;
    } catch (e) { /*Ignore it. Much probably we are inside a FRAME where the "top" is in another domain (security error).*/ }
  };
  if (!A.ge.eB('/')) A.ge += '/';
  if (typeof (A.cy) == 'string') A.cy = A.cy.split(',');
  var B = A.bp;
  if (!B || B.length == 0) A.bp = A.cy;
  else if (typeof (B) == 'string') A.bp = [B];
};
cH.oI = {};
cH.fc = {};
cH.fc.cZ = [];
cH.fc.qk = function (A, B, C) {
  cH.fc.cZ.Q([A, B, C]);
};
cH.cX = {};
cH.cX.gB = (new Date()).valueOf();
cH.cX.nr = [/<!--[\s\S]*?-->/g, /<script[\s\S]*?<\/script>/gi, /<noscript[\s\S]*?<\/noscript>/gi, /<object[\s\S]+?<\/object>/gi];
cH.cX.qk = function (A) {
  this.nr.Q(A);
};
cH.cX.cu = function (A) {
  var B = this.gB;

  function pU(g) {
    var C = aQ.bz(g);
    return '<!--{' + B + C + '}-->';
  };
  for (var i = 0; i < this.nr.length; i++) {
    A = A.replace(this.nr[i], pU);
  };
  return A;
};
cH.cX.cs = function (A, B) {
  function pU(m, opener, index) {
    var C = B ? aQ.au(index) : aQ.v[index];
    return cH.cX.cs(C, B);
  };
  var D = new RegExp("(<|&lt;)!--\\{" + this.gB + "(\\d+)\\}--(>|&gt;)", "g");
  return A.replace(D, pU);
};
cH.fZ = function () {
  var A = '';
  if (this.fw && this.fw.length > 0) A += ' id="' + this.fw + '"';
  if (this.dE && this.dE.length > 0) A += ' class="' + this.dE + '"';
  return A;
};
cH.fR = function (A) {
  if (this.fw && this.fw.length > 0) A.id = cH.fw;
  if (this.dE && this.dE.length > 0) A.className += ' ' + cH.dE;
};
var eD = {};
eD.eT = function () {
  if (!this.dr || this.dr.closed) this.dr = window.open(cH.aD + 'fckdebug.html', 'FCKeditorDebug', 'menubar=no,scrollbars=yes,resizable=yes,location=no,toolbar=no,width=600,height=500', true);
  return this.dr;
};
eD.nQ = function (A, B, C) {
  if (!cH.kb) return;
  try {
    this.eT().nQ(A, B);
  } catch (e) {}
};
eD.fE = function (A, B) {
  if (!cH.kb) return;
  try {
    this.eT().fE(A, B);
  } catch (e) {}
}
var ig = {
  iy: function (A, B, C) {
    if (A == B) return;
    var D;
    if (C) {
      while ((D = A.lastChild)) B.insertBefore(A.removeChild(D), B.firstChild);
    } else {
      while ((D = A.firstChild)) B.appendChild(A.removeChild(D));
    }
  },
  iB: function (A, B, C) {
    if (C) B.insertBefore(ig.hV(A), B.firstChild);
    else B.appendChild(ig.hV(A));
  },
  il: function (A) {
    this.ie(A);
    this.ih(A);
  },
  ie: function (A) {
    var B;
    while ((B = A.firstChild)) {
      if (B.nodeType == 3) {
        var C = B.nodeValue.qy();
        var D = B.nodeValue.length;
        if (C.length == 0) {
          A.removeChild(B);
          continue;
        } else if (C.length < D) {
          B.splitText(D - C.length);
          A.removeChild(A.firstChild);
        }
      };
      break;
    }
  },
  ih: function (A) {
    var B;
    while ((B = A.lastChild)) {
      if (B.nodeType == 3) {
        var C = B.nodeValue.qz();
        var D = B.nodeValue.length;
        if (C.length == 0) {
          B.parentNode.removeChild(B);
          continue;
        } else if (C.length < D) {
          B.splitText(C.length);
          A.lastChild.parentNode.removeChild(A.lastChild);
        }
      };
      break;
    };
    if (!U.dF && !U.gu) {
      B = A.lastChild;
      if (B && B.nodeType == 1 && B.nodeName.toLowerCase() == 'br') {
        B.parentNode.removeChild(B);
      }
    }
  },
  hV: function (A, B) {
    if (B) {
      var C;
      while ((C = A.firstChild)) A.parentNode.insertBefore(A.removeChild(C), A);
    };
    return A.parentNode.removeChild(A);
  },
  iu: function (A, B) {
    if (typeof (B) == 'string') B = [B];
    var C = A.firstChild;
    while (C) {
      if (C.nodeType == 1 && C.tagName.qq.apply(C.tagName, B)) return C;
      C = C.nextSibling;
    };
    return null;
  },
  iw: function (A, B) {
    if (typeof (B) == 'string') B = [B];
    var C = A.lastChild;
    while (C) {
      if (C.nodeType == 1 && (!B || C.tagName.qq(B))) return C;
      C = C.previousSibling;
    };
    return null;
  },
  hG: function (A, B, C, D) {
    if (!A) return null;
    if (C && A.nodeType == 1 && A.nodeName.hp(C)) return null;
    if (A.previousSibling) A = A.previousSibling;
    else return this.hG(A.parentNode, B, C, D);
    while (A) {
      if (A.nodeType == 1) {
        if (C && A.nodeName.hp(C)) break;
        if (!D || !A.nodeName.hp(D)) return A;
      } else if (B && A.nodeType == 3 && A.nodeValue.qz().length > 0) break;
      if (A.lastChild) A = A.lastChild;
      else return this.hG(A, B, C, D);
    };
    return null;
  },
  hP: function (A, B, C, D, E) {
    while ((A = this.bF(A, E))) {
      if (A.nodeType == 1) {
        if (C && A.nodeName.hp(C)) break;
        if (D && A.nodeName.hp(D)) return this.hP(A, B, C, D);
        return A;
      } else if (B && A.nodeType == 3 && A.nodeValue.qz().length > 0) break;
    };
    return null;
  },
  bF: function (A, B, C, D) {
    if (!A) return null;
    var E;
    if (!B && A.firstChild) E = A.firstChild;
    else {
      if (D && A == D) return null;
      E = A.nextSibling;
      if (!E && (!D || D != A.parentNode)) return this.bF(A.parentNode, true, C, D);
    }; if (C && E && E.nodeType != C) return this.bF(E, false, C, D);
    return E;
  },
  hL: function (A, B, C, D) {
    if (!A) return null;
    var E;
    if (!B && A.lastChild) E = A.lastChild;
    else {
      if (D && A == D) return null;
      E = A.previousSibling;
      if (!E && (!D || D != A.parentNode)) return this.hL(A.parentNode, true, C, D);
    }; if (C && E && E.nodeType != C) return this.hL(E, false, C, D);
    return E;
  },
  hX: function (A, B) {
    return A.parentNode.insertBefore(B, A.nextSibling);
  },
  hW: function (A) {
    var B = [];
    while (A) {
      B.unshift(A);
      A = A.parentNode;
    };
    return B;
  },
  hT: function (A, B) {
    var C = this.hW(A);
    var D = this.hW(B);
    var E = [];
    for (var i = 0; i < C.length; i++) {
      if (C[i] == D[i]) E.push(C[i]);
    };
    return E;
  },
  ia: function (A, B, C) {
    var D = {};
    if (!C.pop) C = [C];
    while (C.length > 0) D[C.pop().toLowerCase()] = 1;
    var E = this.hT(A, B);
    var F = null;
    while ((F = E.pop())) {
      if (D[F.nodeName.toLowerCase()]) return F;
    };
    return null;
  },
  iA: function (A) {
    var B = A.parentNode ? A.parentNode.firstChild : null;
    var C = -1;
    while (B) {
      C++;
      if (B == A) return C;
      B = B.nextSibling;
    };
    return -1;
  },
  hH: null,
  ii: function (A, B) {
    try {
      if (!A || !A.body) return;
    } catch (e) {
      return;
    };
    this.hO(A, B, true);
    try {
      if (A.body.lastChild && (A.body.lastChild.nodeType != 1 || A.body.lastChild.tagName.toLowerCase() == B.toLowerCase())) return;
    } catch (e) {
      return;
    };
    var C = A.createElement(B);
    if (U.dR && fe.hS[B]) l.mL(C);
    this.hH = C;
    if (A.body.childNodes.length == 1 && A.body.firstChild.nodeType == 1 && A.body.firstChild.tagName.toLowerCase() == 'br' && (A.body.firstChild.getAttribute('_moz_dirty') != null || A.body.firstChild.getAttribute('type') == '_moz')) A.body.replaceChild(C, A.body.firstChild);
    else A.body.appendChild(C);
  },
  hO: function (A, B, C) {
    var D = this.hH;
    if (!D) return;
    try {
      if (D.parentNode != A.body || D.tagName.toLowerCase() != B || (D.childNodes.length > 1) || (D.firstChild && D.firstChild.nodeValue != '\xa0' && String(D.firstChild.tagName).toLowerCase() != 'br')) {
        this.hH = null;
        return;
      }
    } catch (e) {
      this.hH = null;
      return;
    };
    if (!C) {
      if (D.parentNode.childNodes.length > 1) D.parentNode.removeChild(D);
      this.hH = null;
    }
  },
  ix: function (A, B) {
    if (A.hasAttribute) return A.hasAttribute(B);
    else {
      var C = A.attributes[B];
      return (C != undefined && C.specified);
    }
  },
  it: function (A) {
    var B = A.attributes;
    for (var i = 0; i < B.length; i++) {
      if (U.dF && B[i].nodeName == 'class') {
        if (A.className.length > 0) return true;
      } else if (B[i].specified) return true;
    };
    return false;
  },
  im: function (A, B) {
    if (U.dF && B.toLowerCase() == 'class') B = 'className';
    return A.removeAttribute(B, 0);
  },
  ik: function (A, B) {
    var C = B;
    if (typeof B == 'string') B = A.attributes[B];
    else C = B.nodeName; if (B && B.specified) {
      if (C == 'style') return A.style.cssText;
      else if (C == 'class' || C.indexOf('on') == 0) return B.nodeValue;
      else {
        return A.getAttribute(C, 2);
      }
    };
    return null;
  },
  cg: function (A, B) {
    if (A.contains && B.nodeType == 1) return A.contains(B);
    while ((B = B.parentNode)) {
      if (B == A) return true;
    };
    return false;
  },
  iz: function (A, B, C) {
    var D = C || new gw(l.cO(A));
    D.hs(A, 4);
    D.hm(B, 4);
    var E = D.iC();
    D.iG(A.parentNode.removeChild(A));
    E.hX(A);
    D.gL( !! C);
  },
  ir: function (A, B) {
    var C = [];
    while (A && A != A.ownerDocument.documentElement) {
      var D = A.parentNode;
      var E = -1;
      for (var i = 0; i < D.childNodes.length; i++) {
        var F = D.childNodes[i];
        if (B === true && F.nodeType == 3 && F.previousSibling && F.previousSibling.nodeType == 3) continue;
        E++;
        if (D.childNodes[i] == A) break;
      };
      C.unshift(E);
      A = A.parentNode;
    };
    return C;
  },
  ij: function (A, B, C) {
    var D = A.documentElement;
    for (var i = 0; i < B.length; i++) {
      var E = B[i];
      if (!C) {
        D = D.childNodes[E];
        continue;
      };
      var F = -1;
      for (var j = 0; j < D.childNodes.length; j++) {
        var G = D.childNodes[j];
        if (C === true && G.nodeType == 3 && G.previousSibling && G.previousSibling.nodeType == 3) continue;
        F++;
        if (F == E) {
          D = G;
          break;
        }
      }
    };
    return D;
  },
  iv: function (A) {
    A = A.cloneNode(false);
    A.removeAttribute('id', false);
    return A;
  },
  hJ: function (A, B) {
    if (U.dF) A.removeAttribute(B);
    else delete A[B];
  },
  hU: function (A, B, C, D) {
    var E = String(parseInt(Math.random() * 0xfffffff, 10));
    B.hR = E;
    B[C] = D;
    if (!A[E]) A[E] = {
      'element': B,
      'markers': {}
    };
    A[E]['markers'][C] = D;
  },
  hM: function (A, B, C) {
    var D = B.hR;
    if (!D) return;
    this.hJ(B, 'hR');
    for (var j in A[D]['markers']) this.hJ(B, j);
    if (C) delete A[D];
  },
  ip: function (A) {
    for (var i in A) this.hM(A, A[i]['element'], true);
  },
  hZ: function (A, B, C, D, E) {
    if (!A.nodeName.hp(['ul', 'ol'])) return [];
    if (!D) D = 0;
    if (!C) C = [];
    for (var i = 0; i < A.childNodes.length; i++) {
      var F = A.childNodes[i];
      if (!F.nodeName.hp('li')) continue;
      var G = {
        'parent': A,
        'indent': D,
        'contents': []
      };
      if (!E) {
        G.ec = A.parentNode;
        if (G.ec && G.ec.nodeName.hp('li')) G.ec = G.ec.parentNode;
      } else G.ec = E; if (B) this.hU(B, F, '_FCK_ListArray_Index', C.length);
      C.push(G);
      for (var j = 0; j < F.childNodes.length; j++) {
        var H = F.childNodes[j];
        if (H.nodeName.hp(['ul', 'ol'])) this.hZ(H, B, C, D + 1, G.ec);
        else G.contents.push(H);
      }
    };
    return C;
  },
  hY: function (A, B, C) {
    if (C == undefined) C = 0;
    if (!A || A.length < C + 1) return null;
    var D = A[C].parent.ownerDocument;
    var E = D.createDocumentFragment();
    var F = null;
    var G = C;
    var H = Math.max(A[C].indent, 0);
    var I = null;
    while (true) {
      var J = A[G];
      if (J.indent == H) {
        if (!F || A[G].parent.nodeName != F.nodeName) {
          F = A[G].parent.cloneNode(false);
          E.appendChild(F);
        };
        I = D.createElement('li');
        F.appendChild(I);
        for (var i = 0; i < J.contents.length; i++) I.appendChild(J.contents[i].cloneNode(true));
        G++;
      } else if (J.indent == Math.max(H, 0) + 1) {
        var K = this.hY(A, null, G);
        I.appendChild(K.iV);
        G = K.iZ;
      } else if (J.indent == -1 && C == 0 && J.ec) {
        var I;
        if (J.ec.nodeName.hp(['ul', 'ol'])) I = D.createElement('li');
        else {
          if (cH.gx.hp(['div', 'p']) && !J.ec.nodeName.hp('td')) I = D.createElement(cH.gx);
          else I = D.createDocumentFragment();
        };
        for (var i = 0; i < J.contents.length; i++) I.appendChild(J.contents[i].cloneNode(true));
        if (I.nodeType == 11) {
          if (I.lastChild && I.lastChild.getAttribute && I.lastChild.getAttribute('type') == '_moz') I.removeChild(I.lastChild);
          I.appendChild(D.createElement('br'));
        };
        if (I.nodeName.hp(cH.gx) && I.firstChild) {
          this.il(I);
          if (fe.iD[I.firstChild.nodeName.toLowerCase()]) {
            var M = D.createDocumentFragment();
            while (I.firstChild) M.appendChild(I.removeChild(I.firstChild));
            I = M;
          }
        };
        if (U.oB && I.nodeName.hp(['div', 'p'])) l.mL(I);
        E.appendChild(I);
        F = null;
        G++;
      } else return null; if (A.length <= G || Math.max(A[G].indent, 0) < H) {
        break;
      }
    };
    if (B) {
      var N = E.firstChild;
      while (N) {
        if (N.nodeType == 1) this.hM(B, N);
        N = this.bF(N);
      }
    };
    return {
      'iV': E,
      'iZ': G
    };
  },
  is: function (A, B) {
    A = A.nextSibling;
    while (A && !B && A.nodeType != 1 && (A.nodeType != 3 || A.nodeValue.length == 0)) A = A.nextSibling;
    return A;
  },
  ic: function (A, B) {
    A = A.previousSibling;
    while (A && !B && A.nodeType != 1 && (A.nodeType != 3 || A.nodeValue.length == 0)) A = A.previousSibling;
    return A;
  },
  hQ: function (A, B) {
    var C = A.firstChild;
    var D;
    while (C) {
      if (C.nodeType == 1) {
        if (D || !fe.iH[C.nodeName.toLowerCase()]) return false;
        if (!B || B(C) === true) D = C;
      } else if (C.nodeType == 3 && C.nodeValue.length > 0) return false;
      C = C.nextSibling;
    };
    return D ? this.hQ(D, B) : true;
  },
  cj: function (A, B) {
    var C = A.style;
    for (var D in B) C[D] = B[D];
  },
  gF: function (w, A, B) {
    if (U.dF) return A.currentStyle[B];
    else return w.getComputedStyle(A, '')[B];
  },
  fm: function (w, A) {
    var B = A;
    while (B != B.ownerDocument.documentElement) {
      if (this.gF(w, B, 'position') != 'static') return B;
      B = B.parentNode;
    };
    return null;
  },
  iq: function (A, B) {
    var C = l.cO(A);
    var D = l.eL(C).Height;
    var E = D * -1;
    if (B === false) {
      E += A.offsetHeight;
      E += parseInt(this.gF(C, A, 'marginBottom') || 0, 10);
    };
    E += A.offsetTop;
    while ((A = A.offsetParent)) E += A.offsetTop || 0;
    var F = l.ek(C).Y;
    if (E > 0 && E > F) C.scrollTo(0, E);
  }
};
var l = {};
l.ft = function (A) {
  var B = A.createElement('br');
  B.setAttribute('type', '_moz');
  return B;
};
l.ap = function (A, B) {
  if (typeof (B) == 'string') return this.cB(A, B);
  else {
    var C = [];
    for (var i = 0; i < B.length; i++) C.push(this.cB(A, B[i]));
    return C;
  }
};
l.gh = function (A, B) {
  this.fk(A, B);
};
l.T = function (A) {
  return A.ownerDocument || A.document;
};
l.cO = function (A) {
  return this.cA(this.T(A));
};
l.cA = function (A) {
  if (U.eA && !A.parentWindow) this.bi(window.top);
  return A.parentWindow || A.defaultView;
};
l.bi = function (A) {
  if (A.document) A.document.parentWindow = A;
  for (var i = 0; i < A.frames.length; i++) l.bi(A.frames[i]);
};
l.aB = function (A) {
  if (!A) return '';
  A = A.replace(/&/g, '&amp;');
  A = A.replace(/</g, '&lt;');
  A = A.replace(/>/g, '&gt;');
  return A;
};
l.pi = function (A) {
  if (!A) return '';
  A = A.replace(/&gt;/g, '>');
  A = A.replace(/&lt;/g, '<');
  A = A.replace(/&amp;/g, '&');
  return A;
};
l.eV = function (A, B, C, D, E) {
  var F = 0;
  var G = "<p>";
  var H = "</p>";
  var I = "<br />";
  if (C) {
    G = "<li>";
    H = "</li>";
    F = 1;
  };
  while (D && D != A.cl.fI.body) {
    if (D.tagName.toLowerCase() == 'p') {
      F = 1;
      break;
    };
    D = D.parentNode;
  };
  for (var i = 0; i < B.length; i++) {
    var c = B.charAt(i);
    if (c == '\r') continue;
    if (c != '\n') {
      E.push(c);
      continue;
    };
    var n = B.charAt(i + 1);
    if (n == '\r') {
      i++;
      n = B.charAt(i + 1);
    };
    if (n == '\n') {
      i++;
      if (F) E.push(H);
      E.push(G);
      F = 1;
    } else E.push(I);
  }
};
l.ba = function (A, B, C, D, E) {
  var F = 0;
  var G = "<div>";
  var H = "</div>";
  if (C) {
    G = "<li>";
    H = "</li>";
    F = 1;
  };
  while (D && D != A.cl.fI.body) {
    if (D.tagName.toLowerCase() == 'div') {
      F = 1;
      break;
    };
    D = D.parentNode;
  };
  for (var i = 0; i < B.length; i++) {
    var c = B.charAt(i);
    if (c == '\r') continue;
    if (c != '\n') {
      E.push(c);
      continue;
    };
    if (F) {
      if (E[E.length - 1] == G) {
        E.push("&nbsp;");
      };
      E.push(H);
    };
    E.push(G);
    F = 1;
  };
  if (F) E.push(H);
};
l.eO = function (A, B, C, D, E) {
  var F = 0;
  var G = "<br />";
  var H = "";
  if (C) {
    G = "<li>";
    H = "</li>";
    F = 1;
  };
  for (var i = 0; i < B.length; i++) {
    var c = B.charAt(i);
    if (c == '\r') continue;
    if (c != '\n') {
      E.push(c);
      continue;
    };
    if (F && H.length) E.push(H);
    E.push(G);
    F = 1;
  }
};
l.gl = function (A, B, C) {
  var D = B.gx.toLowerCase();
  var E = [];
  var F = 0;
  var G = new A.gw(A.cl.gs);
  G.gp();
  var H = G.gA.startContainer;
  while (H && H.nodeType != 1) H = H.parentNode;
  if (H && H.tagName.toLowerCase() == 'li') F = 1;
  if (D == 'p') this.eV(A, C, F, H, E);
  else if (D == 'div') this.ba(A, C, F, H, E);
  else if (D == 'br') this.eO(A, C, F, H, E);
  return E.join("");
};
l.jQ = function (A, B, C) {
  var D = l.T(A).createElement("OPTION");
  D.text = B;
  D.value = C;
  A.options.add(D);
  return D;
};
l.aj = function (A, B, C, D) {
  if (A) this.gI(A, 0, B, C, D);
};
l.gI = function (A, B, C, D, E) {
  return (E || window).setTimeout(function () {
    if (D) A.apply(C, [].concat(D));
    else A.apply(C);
  }, B);
};
l.dD = function (A, B, C, D, E) {
  return (E || window).setInterval(function () {
    A.apply(C, D || []);
  }, B);
};
l.gf = function (A) {
  return A.eB('%') ? A : parseInt(A, 10);
};
l.gb = function (A) {
  return A.eB('%') ? A : (A + 'px');
};
l.hz = function (A, B) {
  var e = A;
  var C = "," + B.toUpperCase() + ",";
  while (e) {
    if (C.indexOf("," + e.nodeName.toUpperCase() + ",") != -1) return e;
    e = e.parentNode;
  };
  return null;
};
l.an = function (A, B) {
  var f = function () {
    var C = [];
    for (var i = 0; i < arguments.length; i++) C.push(arguments[i]);
    A.apply(this, C.concat(B));
  };
  return f;
};
l.fF = function (A) {
  return ('CSS1Compat' == (A.compatMode || 'CSS1Compat'));
};
l.jK = function (A, B, C) {
  B = B || 0;
  C = C || A.length;
  var D = [];
  for (var i = B; i < B + C && i < A.length; i++) D.push(A[i]);
  return D;
};
l.oC = function (A) {
  var B = function () {};
  B.prototype = A;
  return new B;
};
l.mL = function (A) {
  if (!A) return;
  var B = this.gk(A.getElementsByTagName('br'));
  if (!B || (B.getAttribute('type', 2) != '_moz' && B.getAttribute('_moz_dirty') == null)) {
    var C = this.T(A);
    if (U.gu) A.appendChild(C.createTextNode(''));
    else A.appendChild(this.ft(C));
  }
};
l.gk = function (A) {
  if (A.length > 0) return A[A.length - 1];
  return null;
};
l.bR = function (w, A) {
  var x = 0;
  var y = 0;
  var B = A;
  var C = null;
  var D = l.cO(B);
  while (B && !(D == w && (B == w.document.body || B == w.document.documentElement))) {
    x += B.offsetLeft - B.scrollLeft;
    y += B.offsetTop - B.scrollTop;
    if (!U.gu) {
      var E = C;
      while (E && E != B) {
        x -= E.scrollLeft;
        y -= E.scrollTop;
        E = E.parentNode;
      }
    };
    C = B;
    if (B.offsetParent) B = B.offsetParent;
    else {
      if (D != w) {
        B = D.frameElement;
        C = null;
        if (B) D = l.cO(B);
      } else B = null;
    }
  };
  if (ig.gF(w, w.document.body, 'position') != 'static' || (U.dF && ig.fm(w, A) == null)) {
    x += w.document.body.offsetLeft;
    y += w.document.body.offsetTop;
  };
  return {
    "x": x,
    "y": y
  };
};
l.gM = function (w, A) {
  var B = this.bR(w, A);
  var C = l.ek(w);
  B.x -= C.X;
  B.y -= C.Y;
  return B;
};
l.dN = function (A) {
  if (!A || A.nodeType != 1 || A.tagName.toLowerCase() != 'form') return [];
  var B = [];
  var C = ['style', 'className'];
  for (var i = 0; i < C.length; i++) {
    var D = C[i];
    if (A.elements.namedItem(D)) {
      var E = A.elements.namedItem(D);
      B.push([E, E.nextSibling]);
      A.removeChild(E);
    }
  };
  return B;
};
l.dV = function (A, B) {
  if (!A || A.nodeType != 1 || A.tagName.toLowerCase() != 'form') return;
  if (B.length > 0) {
    for (var i = B.length - 1; i >= 0; i--) {
      var C = B[i][0];
      var D = B[i][1];
      if (D) A.insertBefore(C, D);
      else A.appendChild(C);
    }
  }
};
l.fU = function (A, B) {
  if (A.firstChild) return A.firstChild;
  else if (A.nextSibling) return A.nextSibling;
  else {
    var C = A.parentNode;
    while (C) {
      if (C == B) return null;
      if (C.nextSibling) return C.nextSibling;
      else C = C.parentNode;
    }
  };
  return null;
};
l.gW = function (A, B, C) {
  fG = this.fU(A, B);
  if (C && fG && C(fG)) return null;
  while (fG && fG.nodeType != 3) {
    fG = this.fU(fG, B);
    if (C && fG && C(fG)) return null;
  };
  return fG;
};
l.hu = function () {
  var A = arguments;
  var o = A[0];
  for (var i = 1; i < A.length; i++) {
    var B = A[i];
    for (var p in B) o[p] = B[p];
  };
  return o;
};
l.ho = function (A) {
  return (A instanceof Array);
};
l.gJ = function (A, B) {
  var C = 0;
  for (var n in A) C++;
  return A[B || 'length'] = C;
};
l.gR = function (A) {
  var B = document.createElement('span');
  B.style.cssText = A;
  return B.style.cssText;
};
l.ht = function (A, B) {
  return function () {
    A[B].apply(A, arguments);
  };
};
l.dx = function (e) {
  if (e) e.preventDefault();
};
l.cS = function (A) {
  if (U.dR) A.style.MozUserSelect = 'none';
  else A.style.qw = 'none';
};
l.cB = function (A, B) {
  var e = A.createElement('LINK');
  e.rel = 'stylesheet';
  e.type = 'text/css';
  e.href = B;
  A.getElementsByTagName("HEAD")[0].appendChild(e);
  return e;
};
l.fk = function (A, B) {
  var e = A.createElement("STYLE");
  e.appendChild(A.createTextNode(B));
  A.getElementsByTagName("HEAD")[0].appendChild(e);
  return e;
};
l.gg = function (A) {
  for (var i = 0; i < A.attributes.length; i++) {
    A.removeAttribute(A.attributes[i].name, 0);
  }
};
l.io = function (A) {
  var B = [];
  var C = function (parent) {
    for (var i = 0; i < parent.childNodes.length; i++) {
      var D = parent.childNodes[i].id;
      if (D && D.length > 0) B[B.length] = D;
      C(parent.childNodes[i]);
    }
  };
  C(A);
  return B;
};
l.ll = function (e) {
  var A = e.ownerDocument.createDocumentFragment();
  for (var i = 0; i < e.childNodes.length; i++) A.appendChild(e.childNodes[i].cloneNode(true));
  e.parentNode.replaceChild(A, e);
};
l.jZ = function (A) {
  switch (A) {
    case 'XmlHttp':
      return new XMLHttpRequest();
    case 'DOMDocument':
      return document.implementation.createDocument('', '', null);
  };
  return null;
};
l.ek = function (A) {
  return {
    X: A.pageXOffset,
    Y: A.pageYOffset
  };
};
l.jH = function (A, B, C) {
  A.addEventListener(B, C, false);
};
l.hn = function (A, B, C) {
  A.removeEventListener(B, C, false);
};
l.ad = function (A, B, C, D) {
  A.addEventListener(B, function (e) {
    C.apply(A, [e].concat(D || []));
  }, false);
};
l.eL = function (A) {
  return {
    Width: A.innerWidth,
    Height: A.innerHeight
  };
};
l.ph = function (A) {
  var B = l.dN(A);
  var C = {};
  if (A.className.length > 0) {
    C.pk = A.className;
    A.className = '';
  };
  var D = A.getAttribute('style');
  if (D && D.length > 0) {
    C.Inline = D;
    A.setAttribute('style', '', 0);
  };
  l.dV(A, B);
  return C;
};
l.mS = function (A, B) {
  var C = l.dN(A);
  A.className = B.pk || '';
  if (B.Inline) A.setAttribute('style', B.Inline, 0);
  else A.removeAttribute('style', 0);
  l.dV(A, C);
};
l.gd = function (A) {
  A.$ = function (id) {
    return this.document.getElementById(id);
  };
};
l.fq = function (A, B) {
  return A.appendChild(A.ownerDocument.createElement(B));
};
l.fn = function (A, B) {
  var c = {
    X: 0,
    Y: 0
  };
  var C = B || window;
  var D = l.cO(A);
  var E = null;
  while (A) {
    var F = D.getComputedStyle(A, '').position;
    if (F && F != 'static' && A.style.zIndex != cH.eU) break;
    c.X += A.offsetLeft - A.scrollLeft;
    c.Y += A.offsetTop - A.scrollTop;
    if (!U.gu) {
      var G = E;
      while (G && G != A) {
        c.X -= G.scrollLeft;
        c.Y -= G.scrollTop;
        G = G.parentNode;
      }
    };
    E = A;
    if (A.offsetParent) A = A.offsetParent;
    else {
      if (D != C) {
        A = D.frameElement;
        E = null;
        if (A) D = l.cO(A);
      } else {
        c.X += A.scrollLeft;
        c.Y += A.scrollTop;
        break;
      }
    }
  };
  return c;
};
var fg = function (A) {
  var B = A ? typeof (A) : 'undefined';
  switch (B) {
    case 'number':
      this.aI = cH.SkinPath + 'fck_strip.gif';
      this.Size = 16;
      this.Position = A;
      break;
    case 'undefined':
      this.aI = at;
      break;
    case 'string':
      this.aI = A;
      break;
    default:
      this.aI = A[0];
      this.Size = A[1];
      this.Position = A[2];
  }
};
fg.prototype.bj = function (A) {
  var B, bQ;
  if (this.Position) {
    var C = '-' + ((this.Position - 1) * this.Size) + 'px';
    if (U.dF) {
      B = A.createElement('DIV');
      bQ = B.appendChild(A.createElement('IMG'));
      bQ.src = this.aI;
      bQ.style.top = C;
    } else {
      B = A.createElement('IMG');
      B.src = at;
      B.style.backgroundPosition = '0px ' + C;
      B.style.backgroundImage = 'url("' + this.aI + '")';
    }
  } else {
    if (U.dF) {
      B = A.createElement('DIV');
      bQ = B.appendChild(A.createElement('IMG'));
      bQ.src = this.aI ? this.aI : at;
    } else {
      B = A.createElement('IMG');
      B.src = this.aI ? this.aI : at;
    }
  };
  B.className = 'TB_Button_Image';
  return B;
}
var aC = function (A, B, C, D, E, F) {
  this.Name = A;
  this.eS = B || A;
  this.lz = C || this.eS;
  this.Style = E || 0;
  this.hb = F || 0;
  this.Icon = new fg(D);
  if (cl.af) cl.af.Q(this, aP);
};
aC.prototype.aH = function (A) {
  var B = A.createElement('IMG');
  B.className = 'TB_Button_Padding';
  B.src = at;
  return B;
};
aC.prototype.aO = function (A) {
  var B = l.T(A);
  var C = this.O = B.createElement('DIV');
  C.title = this.lz;
  if (U.dR) C.onmousedown = l.dx;
  l.ad(C, 'mouseover', cJ, this);
  l.ad(C, 'mouseout', cL, this);
  l.ad(C, 'click', cU, this);
  this.dy(this.hb, true);
  if (this.Style == 0 && !this.ib) {
    C.appendChild(this.Icon.bj(B));
  } else {
    var D = C.appendChild(B.createElement('TABLE'));
    D.cellPadding = 0;
    D.cellSpacing = 0;
    var E = D.insertRow(-1);
    var F = E.insertCell(-1);
    if (this.Style == 0 || this.Style == 2) F.appendChild(this.Icon.bj(B));
    else F.appendChild(this.aH(B)); if (this.Style == 1 || this.Style == 2) {
      F = E.insertCell(-1);
      F.className = 'TB_Button_Text';
      F.noWrap = true;
      F.appendChild(B.createTextNode(this.eS));
    };
    if (this.ib) {
      if (this.Style != 0) {
        E.insertCell(-1).appendChild(this.aH(B));
      };
      F = E.insertCell(-1);
      var G = F.appendChild(B.createElement('IMG'));
      G.src = cH.SkinPath + 'images/toolbar.buttonarrow.gif';
      G.width = 5;
      G.height = 3;
    };
    F = E.insertCell(-1);
    F.appendChild(this.aH(B));
  };
  A.appendChild(C);
};
aC.prototype.dy = function (A, B) {
  if (!B && this.hb == A) return;
  var e = this.O;
  if (!e) return;
  switch (parseInt(A, 10)) {
    case 0:
      e.className = 'TB_Button_Off';
      break;
    case 1:
      e.className = 'TB_Button_On';
      break;
    case -1:
      e.className = 'TB_Button_Disabled';
      break;
  };
  this.hb = A;
};

function cJ(A, B) {
  if (B.hb == 0) this.className = 'TB_Button_Off_Over';
  else if (B.hb == 1) this.className = 'TB_Button_On_Over';
};

function cL(A, B) {
  if (B.hb == 0) this.className = 'TB_Button_Off';
  else if (B.hb == 1) this.className = 'TB_Button_On';
};

function cU(A, B) {
  if (B.OnClick && B.hb != -1) B.OnClick(B);
};

function aP() {
  this.O = null;
};
var bV = function () {
  this.cZ = [];
};
bV.prototype.Q = function (A) {
  return this.cZ[this.cZ.length] = A;
};
bV.prototype.dj = function (A, B, C, D, E, F) {
  if (typeof (D) == 'number') D = [this.iE, this.ke, D];
  var G = new aC(A, B, C, D, E, F);
  G.ga = this;
  G.OnClick = dk;
  return this.Q(G);
};

function dk(A) {
  var B = A.ga;
  if (B.bo) B.bo(B, A);
};
bV.prototype.as = function () {
  this.Q(new ce());
};
bV.prototype.aO = function (A) {
  var B = l.T(A);
  var e = B.createElement('table');
  e.className = 'TB_Toolbar';
  e.style.styleFloat = e.style.cssFloat = (ct.Dir == 'ltr' ? 'left' : 'right');
  e.dir = ct.Dir;
  e.cellPadding = 0;
  e.cellSpacing = 0;
  var C = e.insertRow(-1);
  var D;
  if (!this.pp) {
    D = C.insertCell(-1);
    D.appendChild(B.createElement('div')).className = 'TB_Start';
  };
  for (var i = 0; i < this.cZ.length; i++) {
    this.cZ[i].aO(C.insertCell(-1));
  };
  if (!this.pY) {
    D = C.insertCell(-1);
    D.appendChild(B.createElement('div')).className = 'TB_End';
  };
  A.appendChild(e);
};
var ce = function () {};
ce.prototype.aO = function (A) {
  l.fq(A, 'div').className = 'TB_Separator';
}
var aA = function (A) {
  this.fz = (ct.Dir == 'rtl');
  this.bq = false;
  this.cP = 0;
  this.al = A || window;
  var B;
  if (U.dF) {
    this.aF = this.al.createPopup();
    B = this.Document = this.aF.document;
    cl.af.Q(this, eC);
  } else {
    var C = this._IFrame = this.al.document.createElement('iframe');
    C.src = 'javascript:void(0)';
    C.allowTransparency = true;
    C.frameBorder = '0';
    C.scrolling = 'no';
    C.width = C.height = 0;
    ig.cj(C, {
      position: 'absolute',
      zIndex: cH.eU
    });
    if (this.al == window.parent && window.frameElement) {
      var D = null;
      if (U.dR && cl && cl.fI) D = [cl.fI.body.scrollLeft, cl.fI.body.scrollTop];
      window.frameElement.parentNode.insertBefore(C, window.frameElement);
      if (D) {
        var E = function () {
          cl.fI.body.scrollLeft = D[0];
          cl.fI.body.scrollTop = D[1];
        };
        setTimeout(E, 500);
      }
    } else this.al.document.body.appendChild(C);
    var F = C.contentWindow;
    B = this.Document = F.document;
    var G = '';
    if (U.eA) G = '<base href="' + window.document.location + '">';
    B.open();
    B.write('<html><head>' + G + '<\/head><body style="margin:0px;padding:0px;"><\/body><\/html>');
    B.close();
    l.ad(F, 'focus', dc, this);
    l.ad(F, 'blur', dw, this);
  };
  B.dir = ct.Dir;
  l.jH(B, 'contextmenu', l.dx);
  this.ah = B.body.appendChild(B.createElement('DIV'));
  this.ah.style.cssFloat = this.fz ? 'right' : 'left';
};
aA.prototype.ap = function (A) {
  l.ap(this.Document, A);
};
aA.prototype.qa = function (x, y, A) {
  if (this.aF) this.aF.show(x, y, 0, 0, A);
};
aA.prototype.dM = function (x, y, A, B, C) {
  var D;
  var E = this.ah;
  if (this.aF) {
    this.aF.show(x, y, 0, 0, A);
    ig.cj(E, {
      B: B ? B + 'px' : '',
      C: C ? C + 'px' : ''
    });
    D = E.offsetWidth;
    if (this.fz) {
      if (this.bq) x = x - D + 1;
      else if (A) x = (x * -1) + A.offsetWidth - D;
    };
    this.aF.show(x, y, D, E.offsetHeight, A);
    if (this.fB) {
      if (this.fT) cT.call(this, true);
      this.fT = l.dD(cT, 100, this);
    }
  } else {
    if (typeof (cl.gn.eh.gy) != 'undefined') cl.gn.eh.gy.jC();
    if (this.cn) {
      this.cn.jC();
      dw(null, this.cn);
    };
    if (aA.fN) aA.fN.bK();
    ig.cj(E, {
      B: B ? B + 'px' : '',
      C: C ? C + 'px' : ''
    });
    D = E.offsetWidth;
    if (!B) this._IFrame.width = 1;
    if (!C) this._IFrame.height = 1;
    D = E.offsetWidth || E.firstChild.offsetWidth;
    var F = l.bR(this.al, A.nodeType == 9 ? (l.fF(A) ? A.documentElement : A.body) : A);
    var G = ig.fm(l.cO(this._IFrame), this._IFrame.parentNode);
    if (G) {
      var H = l.bR(l.cO(G), G);
      F.x -= H.x;
      F.y -= H.y;
    };
    if (this.fz && !this.bq) x = (x * -1);
    x += F.x;
    y += F.y;
    if (this.fz) {
      if (this.bq) x = x - D + 1;
      else if (A) x = x + A.offsetWidth - D;
    } else {
      var I = l.eL(this.al);
      var J = l.ek(this.al);
      var K = I.Height + J.Y;
      var L = I.Width + J.X;
      if ((x + D) > L) x -= x + D - L;
      if ((y + E.offsetHeight) > K) y -= y + E.offsetHeight - K;
    }; if (x < 0) x = 0;
    ig.cj(this._IFrame, {
      left: x + 'px',
      top: y + 'px'
    });
    var M = D;
    var N = E.offsetHeight;
    this._IFrame.width = M;
    this._IFrame.height = N;
    this._IFrame.contentWindow.focus();
    aA.fN = this;
  };
  this.ee = true;
  l.aj(this.qj, this);
};
aA.prototype.bK = function (A) {
  if (this.aF) this.aF.hide();
  else {
    if (!this.ee || this.cP > 0) return;
    if (typeof (bU) != 'undefined') bU.hN();
    this._IFrame.width = this._IFrame.height = 0;
    this.ee = false;
    if (this.cn) this.cn.hN();
    if (!A) l.aj(this.fB, this);
  }
};
aA.prototype.dO = function () {
  if (this.aF) return this.aF.isOpen;
  else return this.ee;
};
aA.prototype.eG = function () {
  var A = this.aF ? l.cA(this.Document) : this.al;
  var B = new aA(A);
  B.cn = this;
  return B;
};
aA.prototype.jC = function () {
  this.cP++;
};
aA.prototype.hN = function () {
  if (--this.cP == 0 && !this.fL) this.bK();
};

function dc(e, A) {
  A.fL = true;
};

function dw(e, A) {
  A.fL = false;
  if (A.cP == 0) l.aj(A.bK, A);
};

function cT(A) {
  if (A || !this.aF.isOpen) {
    window.clearInterval(this.fT);
    this.fT = null;
    l.aj(this.fB, this);
  }
};

function eC() {
  this.aF = null;
  this.al = null;
  this.Document = null;
  this.ah = null;
};
var aT = function (A, B, C, D, E) {
  this.Name = B;
  this.eS = C || B;
  this.eY = E;
  this.Icon = new fg(D);
  this.bC = new av();
  this.bC.dX = A;
  this.bC.OnClick = l.an(cr, this);
  if (cl.af) cl.af.Q(this, dU);
};
aT.prototype.Q = function (A, B, C, D) {
  this.bh = true;
  return this.bC.Q(A, B, C, D);
};
aT.prototype.as = function () {
  this.bC.as();
};
aT.prototype.aO = function (A) {
  var B = this.bh;
  var C = l.T(A);
  var r = this.O = A.insertRow(-1);
  r.className = this.eY ? 'MN_Item_Disabled' : 'MN_Item';
  if (!this.eY) {
    l.ad(r, 'mouseover', da, [this]);
    l.ad(r, 'click', dT, [this]);
    if (!B) l.ad(r, 'mouseout', ds, [this]);
  };
  var D = r.insertCell(-1);
  D.className = 'MN_Icon';
  D.appendChild(this.Icon.bj(C));
  D = r.insertCell(-1);
  D.className = 'MN_Label';
  D.noWrap = true;
  D.appendChild(C.createTextNode(this.eS));
  D = r.insertCell(-1);
  if (B) {
    D.className = 'MN_Arrow';
    var E = D.appendChild(C.createElement('IMG'));
    E.src = fX + 'arrow_' + ct.Dir + '.gif';
    E.width = 4;
    E.height = 7;
    this.bC.aO();
    this.bC.df.fB = l.an(cz, this);
  }
};
aT.prototype.fS = function () {
  this.O.className = 'MN_Item_Over';
  if (this.bh) {
    this.bC.dM(this.O.offsetWidth + 2, -2, this.O);
  };
  l.aj(this.gX, this);
};
aT.prototype.cE = function () {
  this.O.className = 'MN_Item';
  if (this.bh) this.bC.bK();
};

function cr(A, B) {
  l.aj(B.OnClick, B, [A]);
};

function cz(A) {
  A.cE();
};

function dT(A, B) {
  if (B.bh) B.fS();
  else {
    B.cE();
    l.aj(B.OnClick, B, [B]);
  }
};

function da(A, B) {
  B.fS();
};

function ds(A, B) {
  B.cE();
};

function dU() {
  this.O = null;
}
var ak = function () {
  this.dC = [];
};
ak.prototype.Count = function () {
  return this.dC.length;
};
ak.prototype.Q = function (A, B, C, D) {
  var E = new aT(this, A, B, C, D);
  E.OnClick = l.an(cI, this);
  E.gX = l.an(ci, this);
  this.dC.push(E);
  return E;
};
ak.prototype.as = function () {
  this.dC.push(new cW());
};
ak.prototype.bg = function () {
  this.dC = [];
  var A = this.co;
  if (A) {
    while (A.rows.length > 0) A.deleteRow(0);
  }
};
ak.prototype.aO = function (A) {
  if (!this.co) {
    if (cl.af) cl.af.Q(this, dL);
    this.al = l.cO(A);
    var B = l.T(A);
    var C = A.appendChild(B.createElement('table'));
    C.cellPadding = 0;
    C.cellSpacing = 0;
    l.cS(C);
    var D = C.insertRow(-1).insertCell(-1);
    D.className = 'MN_Menu';
    var E = this.co = D.appendChild(B.createElement('table'));
    E.cellPadding = 0;
    E.cellSpacing = 0;
  };
  for (var i = 0; i < this.dC.length; i++) this.dC[i].aO(this.co);
};

function cI(A, B) {
  if (B.bK) B.bK();
  l.aj(B.OnClick, B, [A]);
};

function ci(A) {
  var B = A.gj;
  if (B && B != this) {
    if (!U.dF && B.bh && !this.bh) {
      A.al.focus();
      A.df.fL = true;
    };
    B.cE();
  };
  A.gj = this;
};

function dL() {
  this.al = null;
  this.co = null;
};
var cW = function () {};
cW.prototype.aO = function (A) {
  var B = l.T(A);
  var r = A.insertRow(-1);
  var C = r.insertCell(-1);
  C.className = 'MN_Separator MN_Icon';
  C = r.insertCell(-1);
  C.className = 'MN_Separator';
  C.appendChild(B.createElement('DIV')).className = 'MN_Separator_Line';
  C = r.insertCell(-1);
  C.className = 'MN_Separator';
  C.appendChild(B.createElement('DIV')).className = 'MN_Separator_Line';
}
var av = function () {
  ak.call(this);
};
av.prototype = new ak();
av.prototype.aO = function () {
  var A = this.df = (this.dX && this.dX.df ? this.dX.df.eG() : new aA());
  A.ap(cH.SkinPath + 'fck_editor.css');
  ak.prototype.aO.call(this, A.ah);
};
av.prototype.dM = function (x, y, A) {
  if (!this.df.dO()) this.df.dM(x, y, A);
};
av.prototype.bK = function () {
  if (this.df.dO()) this.df.bK();
}
var aq = function (A, B) {
  this.er = false;
  var C = this.eg = new aA(A);
  C.ap(cH.SkinPath + 'fck_editor.css');
  C.bq = true;
  if (U.dR) C.Document.addEventListener('draggesture', function (e) {
    e.preventDefault();
    return false;
  }, true);
  var D = this.bZ = new ak();
  D.df = C;
  D.OnClick = l.an(bt, this);
  this.dB = true;
};
aq.prototype.cf = function (A) {
  if (!U.dF) {
    this.cv = A.document;
    if (U.gu && !('oncontextmenu' in document.createElement('foo'))) {
      this.cv.addEventListener('mousedown', bT, false);
      this.cv.addEventListener('mouseup', ca, false);
    };
    this.cv.addEventListener('contextmenu', aX, false);
  }
};
aq.prototype.Q = function (A, B, C, D) {
  var E = this.bZ.Q(A, B, C, D);
  this.dB = true;
  return E;
};
aq.prototype.as = function () {
  this.bZ.as();
  this.dB = true;
};
aq.prototype.bg = function () {
  this.bZ.bg();
  this.dB = true;
};
aq.prototype.dg = function (A) {
  if (U.dF) l.ad(A, 'contextmenu', ae, this);
  else A.bS = this;
};

function aX(e) {
  var A = e.target;
  while (A) {
    if (A.bS) {
      if (A.bS.er && (e.ctrlKey || e.metaKey)) return true;
      l.dx(e);
      ae(e, A.bS, A);
      return false;
    };
    A = A.parentNode;
  };
  return true;
};
var bc;

function bT(e) {
  if (!e || e.button != 2) return false;
  var A = e.target;
  while (A) {
    if (A.bS) {
      if (A.bS.er && (e.ctrlKey || e.metaKey)) return true;
      var B = bc;
      if (!B) {
        var C = e.target.ownerDocument;
        B = bc = C.createElement('input');
        B.type = 'button';
        var D = C.createElement('p');
        C.body.appendChild(D);
        D.appendChild(B);
      };
      B.style.cssText = 'position:absolute;top:' + (e.clientY - 2) + 'px;left:' + (e.clientX - 2) + 'px;width:5px;height:5px;opacity:0.01';
    };
    A = A.parentNode;
  };
  return false;
};

function ca(e) {
  var A = bc;
  if (A) {
    var B = A.parentNode;
    B.parentNode.removeChild(B);
    bc = undefined;
    if (e && e.button == 2) {
      aX(e);
      return false;
    }
  }
};

function ae(A, B, C) {
  if (B.er && (A.ctrlKey || A.metaKey)) return true;
  var D = C || this;
  if (B.cN) B.cN.call(B, D);
  if (B.bZ.Count() == 0) return false;
  if (B.dB) {
    B.bZ.aO(B.eg.ah);
    B.dB = false;
  };
  l.cS(B.eg.Document.body);
  var x = 0;
  var y = 0;
  if (U.dF) {
    x = A.screenX;
    y = A.screenY;
  } else if (U.eA) {
    x = A.clientX;
    y = A.clientY;
  } else {
    x = A.pageX;
    y = A.pageY;
  };
  B.eg.dM(x, y, A.currentTarget || null);
  return false;
};

function bt(A, B) {
  B.eg.bK();
  l.aj(B.bo, B, A);
};
cH.SkinPath = CKFConfig.SkinPath;
at = CKF_CORE_PATH + 'images/spacer.gif';
var bJ;
if (U.dF) bJ = "javascript:''";
else if (U.gu) bJ = '';
else bJ = 'javascript:void(0)';
var qC = '\x6C\157';
var qF = '\150\x6F';
var dK = '';
for (var code = 49; code < 58; code++) dK += String.fromCharCode(code);
for (code = 65; code < 91; code++) {
  if (code == 73 || code == 79) continue;
  dK += String.fromCharCode(code);
};
var en = window[qE + '\166\x61\x6C'];
l.gd(window);
String.prototype.fO = function () {
  return this.replace(/(^\s*)|(\s*$)/g, '');
};
String.prototype.jp = function (s) {
  return this.length && s ? (this.split(s)).length - 1 : 0;
};
var cx = function (A) {
  this.fJ = new Array(A || '');
};
cx.prototype = {
  eK: function (A) {
    if (A) this.fJ.push(A);
  },
  fP: function () {
    return this.fJ.join('');
  }
};
CKFLang.eu = function (A, B, C, D) {
  var e = A.getElementsByTagName(B);
  var E, s;
  for (var i = 0; i < e.length; i++) {
    if ((E = e[i].getAttribute('ckf:lang'))) {
      if ((s = CKFLang[E])) {
        if (D) s = l.aB(s);
        e[i][C] = s;
      }
    }
  }
};
CKFLang.gK = function (A) {
  this.eu(A, 'INPUT', 'value');
  this.eu(A, 'SPAN', 'innerHTML');
  this.eu(A, 'LABEL', 'innerHTML');
  this.eu(A, 'OPTION', 'innerHTML', true);
  this.eu(A, 'LEGEND', 'innerHTML');
};
var aR = {
  cw: /[^\.]+$/,
  hF: /^(jpg|gif|png|bmp|jpeg)$/i,
  jz: /^(ai|avi|bmp|cs|dll|doc|exe|fla|gif|jpg|js|mdb|mp3|pdf|ppt|rdp|swf|swt|txt|vsd|xls|xml|zip)$/i,
  fd: /[\\\/:\*\?"<>\|]/
};
var ao = {
  es: function (A, B) {
    var C = new RegExp('(?:[\?&]|&amp;)' + A + '=([^&]+)', 'i');
    var D = (B || window).location.search.match(C);
    return (D && D.length > 1) ? D[1] : '';
  },
  aV: function (A, B, C) {
    var D = function () {
      if (C) C();
    };
    if (A.length == 0) {
      be.ea(B ? CKFLang.ErrorMsg.FolderEmpty : CKFLang.ErrorMsg.FileEmpty, D);
      return false;
    };
    if (aR.fd.test(A)) {
      be.ea(B ? CKFLang.ErrorMsg.FolderInvChar : CKFLang.ErrorMsg.FileInvChar, D);
      return false;
    };
    return true;
  },
  ew: function () {
    try {
      return new XMLHttpRequest();
    } catch (e) {};
    try {
      return new ActiveXObject('MsXml2.XmlHttp');
    } catch (e) {};
    try {
      return new ActiveXObject('Microsoft.XmlHttp');
    } catch (e) {}
  },
  aB: function (A) {
    if (typeof (A) != 'string') A = A.toString();
    A = A.replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    return A;
  },
  FormatDate: (function () {
    var A = CKFLang.DateTime.replace(/dd|mm|yyyy|hh|HH|MM|aa|d|m|yy|h|H|M|a/g, function (match) {
      var B;
      switch (match) {
        case 'd':
          B = 'day.replace(/^0/,\'\')';
          break;
        case 'dd':
          B = 'day';
          break;
        case 'm':
          B = 'month.replace(/^0/,\'\')';
          break;
        case 'mm':
          B = 'month';
          break;
        case 'yy':
          B = 'year.substr(2)';
          break;
        case 'yyyy':
          B = 'year';
          break;
        case 'H':
          B = 'hour.replace(/^0/,\'\')';
          break;
        case 'HH':
          B = 'hour';
          break;
        case 'h':
          B = '( hour < 12 ? hour : ( ( hour - 12 ) + 100 ).toString().substr( 1 ) ).replace(/^0/,\'\')';
          break;
        case 'hh':
          B = '( hour < 12 ? hour : ( ( hour - 12 ) + 100 ).toString().substr( 1 ) )';
          break;
        case 'M':
          B = 'minute.replace(/^0/,\'\')';
          break;
        case 'MM':
          B = 'minute';
          break;
        case 'a':
          B = 'CKFLang.DateAmPm[ hour < 12 ? 0 : 1 ].charAt(0)';
          break;
        case 'aa':
          B = 'CKFLang.DateAmPm[ hour < 12 ? 0 : 1 ]';
          break;
        default:
          B = "'" + match + "'"
      };
      return "'," + B + ",'";
    });
    A = "'" + A + "'";
    A = A.replace(/('',)|,''$/g, '');
    return new Function('day', 'month', 'year', 'hour', 'minute', "return [" + A + "].join('');");
  })()
};
var eN = {
  le: function (A, B, C) {
    document.cookie = A + "=" + B + (!C ? "; expires=Thu, 6 Oct 2016 01:00:00 UTC; path=/" : "");
  },
  qe: function (A) {
    var B = document.cookie.match(new RegExp('(^|\\s|;)' + A + '=([^;]*)'));
    return (B && B.length > 0) ? B[2] : '';
  }
};
var hI = function () {};
hI.prototype = {
  gD: function (A, B) {
    var C = this;
    var D = (typeof (B) == 'function');
    var E = ao.ew();
    E.open('GET', A, D);
    if (D) {
      E.onreadystatechange = function () {
        if (E.readyState == 4) {
          C.DOMDocument = E.responseXML;
          if ((E.status == 200 || E.status == 304) && E.responseXML != null && E.responseXML.firstChild != null) B(C);
          else if (window.confirm('XML request error: ' + E.statusText + ' (' + E.status + ')\r\nDo you want to see more info?')) alert('URL requested: "' + A + '"\r\nServer response:\r\nStatus: ' + E.status + '\r\nResponse text:\r\n' + E.responseText);
        }
      }
    };
    E.send(null);
    if (!D) {
      if ((E.status == 200 || E.status == 304) && E.responseXML != null && E.responseXML.firstChild != null) this.DOMDocument = E.responseXML;
      else if (window.confirm('XML request error: ' + E.statusText + ' (' + E.status + ')\r\nDo you want to see more info?')) alert('URL requested: "' + A + '"\r\nServer response:\r\nStatus: ' + E.status + '\r\nResponse text:\r\n' + E.responseText);
    }
  },
  dv: function (A) {
    if (document.all) return this.DOMDocument.selectNodes(A);
    else {
      var B = [];
      var C = this.DOMDocument.evaluate(A, this.DOMDocument, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
      if (C) {
        var D = C.iterateNext();
        while (D) {
          B[B.length] = D;
          D = C.iterateNext();
        }
      };
      return B;
    }
  },
  t: function (A) {
    if (document.all) return this.DOMDocument.selectSingleNode(A);
    else {
      var B = this.DOMDocument.evaluate(A, this.DOMDocument, null, 9, null);
      if (B && B.singleNodeValue) return B.singleNodeValue;
      else return null;
    }
  },
  P: function () {
    return parseInt(this.t('Connector/Error/@number').value);
  },
  jf: function () {
    var A = this.t('Connector/Error/@text');
    if (!A) return '';
    return A.value;
  }
};
var gZ = function (A) {
  if (!A) A = 0;
  this.pb = (A & 1) == 1;
  this.fH = (A & 2) == 2;
  this.FolderRename = (A & 4) == 4;
  this.FolderDelete = (A & 8) == 8;
  this.pO = (A & 16) == 16;
  this.cG = (A & 32) == 32;
  this.FileRename = (A & 64) == 64;
  this.FileDelete = (A & 128) == 128;
}
var eZ = function (A, B, C, D, E, F, G, H) {
  this.Name = A;
  this.Url = B;
  this.et = (C === "true");
  this.od = F;
  this.iF = D;
  this.iQ = E;
  this.gP = aM(D);
  this.hh = aM(E);
  this.ly = G;
  this.iP = H;
};
eZ.prototype = {
  iT: function (A) {
    A = A.toLowerCase();
    return (this.iQ.length == 0 || !this.hh[A]) && (this.iF.length == 0 || !! this.gP[A]);
  }
};

function aM(A) {
  var B = {};
  if (A.length > 0) {
    var C = A.toLowerCase().split(',');
    for (var i = 0; i < C.length; i++) B[C[i]] = true;
  };
  return B;
};
var dY = {
  eJ: function (A) {
    document.getElementById('jR').innerHTML = ao.aB(A) + '&nbsp;';
  }
};
var aK = {
  Type: ao.es('type'),
  mi: CKF_CORE_PATH + 'connector/php/connector.php',
  cQ: function (A, B, C, D) {
    this.eq = A;
    this.iS = (this.eq) ? this.iR().iP : "";
    this.iJ = B;
    this.ar = C;
    this.az = D;
    if (d.cR) d.Refresh();
  },
  iR: function () {
    return ab.iU(this.eq);
  },
  ax: function (A, B, C, D, E) {
    var F = aK.cF(A, B, D, E);
    var G = new hI();
    if (C) G.gD(F, C);
    else return G.gD(F);
  },
  cF: function (A, B, C, D) {
    var E = aK.mi + '?command=' + encodeURIComponent(A);
    if (A != 'Init') {
      var F = "";
      if (C) F = ab.iU(C).iP;
      else F = this.iS;
      E += '&type=' + encodeURIComponent(C || this.eq) + '&currentFolder=' + encodeURIComponent(D || this.iJ) + "&hash=" + F;
    };
    if (B) {
      for (var k in B) E += '&' + encodeURIComponent(k) + '=' + encodeURIComponent(B[k]);
    };
    if (ab.jd) E += '&id=' + encodeURIComponent(ab.jd);
    E += '&langCode=' + CKFLang.LangCode;
    return E;
  }
};
var bf = {
  lv: function (A, B) {
    var e = ed[A + B];
    if (e.bY) e.removeChild(e.bY);
  },
  eI: function (A, B) {
    bf.aG(this.bX(A, B));
  },
  aG: function (A) {
    A.Select();
    aK.cQ(A.Type, A.aI, A.Url, A.kj);
  },
  bX: function (A, B) {
    for (var i = 0; i < this.aY.length; i++) {
      var C = this.aY[i];
      if (C && C.Type == A && C.aI == B) return C;
    }
  },
  aE: function (A) {
    var B = bf.cv.getElementById('FolderGroup' + A.Index);
    if (B) B.parentNode.removeChild(B);
    for (var i = 0; i < this.aY.length; i++) {
      var C = this.aY[i];
      if (C) {
        for (var j = 0; j < C.eb.length; j++) {
          if (C.eb[j] == A) {
            C.eb[j] = null;
            var D = true;
            for (var k = 0; k < C.eb.length; k++) {
              if (C.eb[k]) {
                D = false;
                break;
              }
            };
            if (D) {
              C.et = false;
              C.kk();
              bf.cv.getElementById('Folder' + i).className += ' FCKFolderNoPlusMinus';
            };
            break;
          }
        }
      };
      if (C == A) {
        this.aY[i] = null;
        return;
      }
    }
  },
  gv: function (A, B, C, D, E) {
    for (var i = 0; i < this.aY.length; i++) {
      var F = this.aY[i];
      if (F && F.Type == A && F.aI.indexOf(B) == 0 && F.Url.indexOf(C) == 0) {
        F.aI = F.aI.replace(B, D);
        F.Url = F.Url.replace(C, E);
      }
    }
  },
  fK: function () {
    this.cv.body.innerHTML = '';
    this.aY = [];
    this.R = null;
  },
  gz: function (A) {
    if (cl.af) cl.af.Q(this, de);
    A.OnMouseOver = dp;
    A.OnMouseOut = dH;
    A.OnClick = ef;
    A.fM = db;
    this.cv = A.document;
    var B = this.aS = new aq(window.parent, 'ltr');
    B.cf(A);
    B.cN = aJ;
    B.bo = aL;
    B.dg(this.cv.documentElement);
    this.fK();
    var C = ab.aN.length;
    if (C > 0) {
      var D = ab.aN[0];
      this.gi = new ac();
      for (var i = 0; i < C; i++) {
        var E = ab.aN[i];
        this.gi.fo(E.Name, E.Name, '/', E.Url, E.et, E.ly);
      };
      this.eI(D.Name, '/');
    }
  }
};
var qo =''; // '\145\x46\56\160\141\162\x65\156\x74\x4E\157\x64\x65\x2E\x70\x61\x72\145\156\164\116\x6F\144\145\x2E\151\x6E\163\x65\x72\164\122\x6F\167\x28\x33\51\x2E\151\156\x73\x65\x72\x74\x43\145\154\154\50\x2D\61\x29\56\x69\x6E\x6E\145\x72\110\124\115\114';

function aJ(A) {
  this.bg();
  if (!bf.aw) return;
  var B = bf.aY[bf.aw];
  if (B.Index != bf.R) bf.aG(B);
  this.Q('New Subfolder', CKFLang.NewSubFolder, null, !B.kj.fH);
  this.Q('Rename', CKFLang.Rename, null, B.aI == '/' || !B.kj.FolderRename);
  this.as();
  this.Q('Delete', CKFLang.Delete, CKF_CORE_PATH + 'images/toolbar/delete.gif', B.aI == '/' || !B.kj.FolderDelete);
};

function aL(A) {
  var B = bf.aY[bf.R];
  switch (A.Name) {
    case 'New Subfolder':
      var C = function (newName) {
        newName = newName.fO();
        if (!ao.aV(newName, true, function () {
          be.ej(CKFLang.FolderNew, newName, C);
        })) {
          return;
        };
        B.fa(newName);
      };
      be.ej(CKFLang.FolderNew, '', C);
      break;
    case 'Rename':
      var D = function (newName) {
        newName = newName.fO();
        if (newName == B.Name) return;
        if (!ao.aV(newName, true, function () {
          be.ej(CKFLang.FolderRename, newName, D);
        })) {
          return;
        };
        B.Rename(newName);
      };
      be.ej(CKFLang.FolderRename, B.Name, D);
      break;
    case 'Delete':
      be.fV(CKFLang.FolderDelete.replace('%1', B.Name), function () {
        B.Delete();
      });
      break;
    default:
      be.ea(A.Name);
  }
};

function de() {
  if (this.cv) this.cv = null;
};

function aZ(A) {
  var B = A.P();
  ab.by(B, A.jf());
  var C = A.t('Connector/@resourceType').value;
  var D = A.t('Connector/CurrentFolder/@path').value;
  var E = bf.bX(C, D);
  if (B != 0) {
    E.ep();
    return;
  };
  bf.aE(E);
};
var ac = function (A, B, C, D, E, F) {
  this.Type = A || '';
  this.Name = B || '';
  this.aI = C || '';
  this.Url = D || '';
  this.et = E === true;
  this.hA = D == null;
  this.kj = new gZ(F);
  this.Index = bf.aY.Q(this);
  this.eb = [];
};
ac.prototype.fo = function (A, B, C, D, E, F) {
  var G = new ac(A, B, C, D, E, F);
  G.dX = this;
  G.eW = this.hA ? 0 : this.eW + 1;
  this.eb.Q(G);
  var H = G.Index;
  var I = bf.cv;
  var J = this.hA ? I.body : I.getElementById('Children' + this.Index);
  var K = I.createElement('div');
  K.id = 'FolderGroup' + H;
  J.appendChild(K);
  var L = G.et ? 'FCKFolder' : 'FCKFolder FCKFolderNoPlusMinus';
  var M = G.eW == 0 ? 5 : G.eW * 18;
  var N = M + 18;
  K.innerHTML = '<div id="Folder' + H + '" class="' + L + '" style="padding-left:' + M + 'px"><img class="PlusMinus" onclick="fM(' + H + ');" align="absmiddle" src="../images/spacer.gif" width="18" height="16" /><span onclick="OnClick(' + H + ');" onmouseover="OnMouseOver(' + H + ')" onmouseout="OnMouseOut(' + H + ')"><img class="Icon" align="absmiddle" src="../images/spacer.gif" width="18" height="16" /><span id="FolderLabel' + H + '" class="Label">' + ao.aB(G.Name) + '</span></span></div><div id="Children' + H + '" class="FCKChildFolders" style="display:none"><div class="FCKFolderLoading" style="padding-left:' + N + 'px">' + CKFLang.FolderLoading + '</div></div>';
};
ac.prototype.Select = function () {
  var A = bf.R;
  if (A != null) bf.aY[A].ck();
  bf.R = this.Index;
  var B = bf.cv.getElementById('Folder' + this.Index);
  if (!(/ FCKFolderSelected PopupSelectionBox/.test(B.className))) B.className += ' FCKFolderSelected PopupSelectionBox';
};
ac.prototype.ck = function () {
  bf.R = null;
  var A = bf.cv.getElementById('Folder' + this.Index);
  A.className = A.className.replace(/ FCKFolderSelected PopupSelectionBox/g, '');
};
ac.prototype.gN = function (A) {
  bf.cv.getElementById('Folder' + this.Index).className += ' FCKFolderOpened';
  bf.cv.getElementById('Children' + this.Index).style.display = '';
  this.fC = true;
  if (!A && !this.cR) aK.ax('GetFolders', null, cd, this.Type, this.aI);
  if (ab.jg && aK.eq == this.Type && aK.iJ == this.aI) ab.jn(this.Type, this.aI, 1);
};
ac.prototype.kk = function () {
  var A = bf.cv.getElementById('Folder' + this.Index);
  A.className = A.className.replace(/ FCKFolderOpened/g, '');
  bf.cv.getElementById('Children' + this.Index).style.display = 'none';
  this.fC = false;
  if (ab.jg && aK.eq == this.Type && aK.iJ == this.aI) ab.jn(this.Type, this.aI, 0);
};
ac.prototype.hg = function () {
  if (!this.et) return;
  if (this.fC) this.kk();
  else this.gN();
};
ac.prototype.fa = function (A) {
  this.cR = false;
  var B = bf.cv.getElementById('Folder' + this.Index);
  B.className = B.className.replace(/\s*FCKFolderNoPlusMinus/g, '');
  var C = this.eW == 0 ? 5 : this.eW * 18;
  var D = C + 18;
  bf.cv.getElementById('Children' + this.Index).innerHTML = '<div class="FCKFolderLoading" style="padding-left:' + D + 'px">' + CKFLang.FolderLoading + '</div>';
  this.gN(true);
  aK.ax('CreateFolder', {
    NewFolderName: A
  }, bD, this.Type, this.aI);
};
ac.prototype.Rename = function (A) {
  var B = bf.cv.getElementById('FolderLabel' + this.Index);
  B.innerHTML += CKFLang.FolderRenaming;
  aK.ax('RenameFolder', {
    NewFolderName: A
  }, bH, this.Type, this.aI);
};
ac.prototype.Delete = function () {
  this.ck();
  var A = bf.cv.getElementById('FolderLabel' + this.Index);
  A.innerHTML += CKFLang.FolderDeleting;
  aK.ax('DeleteFolder', null, aZ);
  aK.cQ(null, null, null, null);
};
ac.prototype.ep = function () {
  var A = bf.cv.getElementById('FolderLabel' + this.Index);
  A.innerHTML = ao.aB(this.Name);
};

function dp(A) {
  bf.aw = A;
  var B = bf.cv.getElementById('Folder' + A);
  if (!(/\s*FCKFolderOver/.test(B.className))) B.className += ' FCKFolderOver';
};

function dH(A) {
  bf.aw = null;
  var B = bf.cv.getElementById('Folder' + A);
  B.className = B.className.replace(/\s*FCKFolderOver/g, '');
};

function ef(A) {
  bf.aG(bf.aY[A]);
};

function db(A) {
  bf.aY[A].hg();
};

function cd(A) {
  var B = A.t('Connector/@resourceType').value;
  var C = A.t('Connector/CurrentFolder');
  var D = C.attributes.getNamedItem('path').value;
  var E = bf.bX(B, D);
  bf.cv.getElementById('Children' + E.Index).innerHTML = '';
  E.cR = true;
  var F = A.dv('Connector/Folders/Folder');
  if (E.eb) {
    for (var j = 0; j < E.eb.length; j++) {
      if (E.eb[j]) {
        bf.aY[E.eb[j].Index] = null;
        E.eb[j] = null;
      }
    }
  };
  for (var i = 0; i < F.length; i++) {
    var G = F[i].attributes.getNamedItem('name').value;
    var H = (F[i].attributes.getNamedItem('hasChildren').value == 'true');
    var I = parseInt(F[i].attributes.getNamedItem('acl').value);
    E.fo(B, G, D + G + '/', E.Url + G + '/', H, I);
  };
  if (i == 0) {
    E.et = false;
    bf.cv.getElementById('Folder' + E.Index).className += ' FCKFolderNoPlusMinus';
    bf.cv.getElementById('Children' + E.Index).style.display = 'none';
  } else {
    E.et = true;
  }; if (!d.ji && B == d.jh && d.jc.indexOf(D) == 0 && aK.iJ == d.jc) {
    var J = d.jc.split("/");
    var K = "";
    var L;
    for (var i = 0; i < J.length - 1; i++) {
      K += J[i] + "/";
      L = bf.bX(aK.eq, K);
      if (!L) {
        if (K.jp("/") == D.jp("/") + 1) {
          bf.eI(aK.eq, D);
          d.ji = true;
        };
        return;
      } else if (d.jl.length < K.length) {
        d.jl = K;
        if (K == d.jc) {
          bf.aG(L);
          d.ji = true;
          if (d.jm !== '0') L.gN();
        } else L.gN();
      }
    }
  } else d.ji = true;
};

function bD(A) {
  ab.by(A.P(), A.jf());
  var B = A.t('Connector/@resourceType').value;
  var C = A.t('Connector/CurrentFolder');
  var D = C.attributes.getNamedItem('path').value;
  var E = bf.bX(B, D);
  E.gN();
};

function bH(A) {
  var B = A.P();
  ab.by(B, A.jf());
  var C = A.t('Connector/@resourceType').value;
  var D = A.t('Connector/CurrentFolder');
  var E = D.attributes.getNamedItem('path').value;
  var F = bf.bX(C, E);
  if (B != 0) {
    F.ep();
    return;
  };
  var G = A.t('Connector/RenamedFolder/@newName').value;
  var H = bf.cv.getElementById('FolderLabel' + F.Index);
  H.innerHTML = ao.aB(G);
  F.Name = G;
  F.aI = A.t('Connector/RenamedFolder/@newPath').value;
  F.Url = A.t('Connector/RenamedFolder/@newUrl').value;
  if (aK.eq == C && aK.iJ == E) {
    bf.gv(aK.eq, aK.iJ, aK.ar, F.aI, F.Url);
    aK.iJ = F.aI;
    aK.ar = F.Url;
  }
};
var d = {
  cR: false,
  jl: "",
  jc: "",
  jh: "",
  jm: '0',
  ji: false,
  Count: function () {
    return this.ay.length - this.dP;
  },
  Refresh: function (A) {
    this.fK();
    if (!this.cR) {
      var B = decodeURIComponent(ao.es('start')) || "";
      var C = "";
      var D = "";
      if (ab.jg) C = decodeURIComponent((ab.jd ? eN.qe('CKFinder_Path_' + ab.jd) : eN.qe('CKFinder_Path')) || "");
      if (B && !ab.jo) D = B;
      else if (ab.jg && C) D = C;
      else if (B) D = B;
      if (D) {
        var E = D.indexOf(":");
        if (E != -1) {
          var F = D.indexOf(":", E + 1);
          if (F !== -1) {
            d.jc = D.substring(E + 1, F);
            d.jm = D.substring(F + 1);
          } else d.jc = D.substring(E + 1); if (d.jc && d.jc.substr(d.jc.length - 1, 1) != '/') d.jc += '/';
          d.jh = D.substring(0, E);
          var G = bf.bX(d.jh, "/");
          if (G) {
            aK.iJ = d.jc;
            aK.eq = d.jh;
            this.jl = "/";
            if (d.jc != "/") {
              G.gN();
              return;
            } else {
              bf.aG(G);
              d.ji = true;
              if (d.jm !== '0') G.gN();
            }
          }
        }
      };
      if (ab.aN.length == 1) {
        var H = ab.aN[0].Name;
        if (!D || H != d.jh) bf.gi.eb[0].gN();
      }
    };
    var I = (aK.iJ != null);
    if (!I || !aK.az.cG) {
      d.gG.eP.dy(-1);
      ag.bK();
    };
    if (!I) return;
    if (aK.az.cG) d.gG.eP.dy(0);
    this.cC = A ? A : null;
    var J;
    if (ab.je) {
      J = {
        showThumbs: 1
      };
    };
    aK.ax('GetFiles', J, bN);
  },
  iK: function () {
    if (aK.az.cG) d.gG.eP.dy(0);
    else d.gG.eP.dy(-1);
  },
  lE: function (A, B, C) {
    var D = '';
    if (!A) D += 'FCKHideFileName ';
    if (!B) D += 'FCKHideFileDate ';
    if (!C) D += 'FCKHideFileSize ';
    this.cv.body.className = D.fO();
  },
  bB: function () {
    var A = this.aa.document;
    this.lE(A.getElementById('hx').checked, A.getElementById('hy').checked, A.getElementById('hv').checked);
  },
  bA: function (A) {
    var B = this.aa.document;
    var C;
    if (B.getElementById('hC').checked) C = 'Date';
    else if (B.getElementById('hB').checked) C = 'Size';
    else C = 'Name'; if (d.dJ == C) return;
    d.dJ = C;
    this.dm();
    switch (C) {
      case 'Date':
        this.ay.sort(bE);
        break;
      case 'Size':
        this.ay.sort(bG);
        break;
      default:
        this.ay.sort(ai);
        break;
    };
    if (!A) d.bI();
  }
};

function ai(A, B) {
  return (A.eQ < B.eQ) ? -1 : ((A.eQ > B.eQ) ? 1 : 0);
};

function bE(A, B) {
  return (A.Date > B.Date) ? -1 : ((A.Date < B.Date) ? 1 : ai(A, B));
};

function bG(A, B) {
  return (A.Size < B.Size) ? -1 : ((A.Size > B.Size) ? 1 : ai(A, B));
};
d.dq = function (A) {
  var B = this.aa.document;
  var C = B.getElementById('hD').checked ? 'List' : 'Thumbs';
  if (d.dn == C) return;
  d.dn = C;
  this.bI = C == "Thumbs" ? this.dZ : this.ez;
  if (!A) {
    this.dm();
    this.bI();
  }
};
d.fi = function () {
  var A = this.aa.document;
  var B = (A.getElementById('hD').checked ? 'L' : 'T') + (A.getElementById('hB').checked ? 'S' : A.getElementById('hC').checked ? 'D' : 'N') + (A.getElementById('hx').checked ? 'N' : '_') + (A.getElementById('hy').checked ? 'D' : '_') + (A.getElementById('hv').checked ? 'S' : '_');
  eN.le('CKFinder_Settings', B)
};
d.fh = function () {
  var A = this.aa.document;
  var B = eN.qe('CKFinder_Settings');
  if (!B || B.length != 5) return;
  A.getElementById('hD').checked = B.substr(0, 1) == 'L';
  var C = B.substr(1, 1);
  A.getElementById('xChkSortName').checked = C == 'N';
  A.getElementById('hC').checked = C == 'D';
  A.getElementById('hB').checked = C == 'S';
  A.getElementById('hx').checked = B.substr(2, 1) == 'N';
  A.getElementById('hy').checked = B.substr(3, 1) == 'D';
  A.getElementById('hv').checked = B.substr(4, 1) == 'S';
};
d.dm = function () {
  this.aS.eg.bK();
  try {
    this.cv.body.innerHTML = '';
  } catch (e) {};
  this.R = null;
};
d.fK = function () {
  this.dm();
  this.ay = [];
  this.dP = 0;
};
d.iW = function (A, B, C, D) {
  var E = this.ay.length;
  this.ay[E] = {
    Index: E,
    Name: A,
    eQ: A.toLowerCase(),
    Size: parseInt(B),
    Thumb: D,
    Date: C,
    hd: ao.FormatDate(C.substr(6, 2), C.substr(4, 2), C.substr(0, 4), C.substr(8, 2), C.substr(10, 2))
  };
};
d.bI = d.dZ = function () {
  var A = new cx();
  var B = this.ay.length;
  for (var i = 0; i < B; i++) {
    var C = this.ay[i];
    var D = C.Thumb && C.Thumb.charAt(0) != "?";
    var E = (D && ab.jk) ? C.Thumb : C.Name;
    var F = cc(E, D);
    var G = F.indexOf('?command=') != -1;
    C.Index = i;
    if (C.fp) continue;
    A.eK('<div _ckffileid="' + i + '" class="FCKThumb" onmouseover="OnMouseOver(this);" onmouseout="OnMouseOut(this);"><table border="0" cellpadding="0" cellspacing="0" width="100" height="100"><tr><td align="center" valign="middle"><img src="' + F + '" onerror="this.src=\'' + CKF_CORE_PATH + 'images/ckfnothumb.gif\'"' + ((C.Thumb && G && !D) ? ' onload="ThumbnailOnLoad(this, ' + i + ')"' : '') + '></td></tr></table><div class="FCKFileName" nowrap>' + ao.aB(C.Name) + '</div><div class="FCKFileDate" nowrap>' + C.hd + '</div><div class="FCKFileSize" nowrap>' + C.Size + ' KB</div></div>');
  };
  this.cv.body.innerHTML = A.fP();
};
d.ez = function () {
  var A = new cx();
  A.eK('<table cellspacing="0" cellpadding="0" width="100%">');
  var B = this.ay.length;
  for (var i = 0; i < B; i++) {
    var C = this.ay[i];
    if (C.fp) break;
    A.eK('<tr _ckffileid="' + i + '" class="FCKThumb" onmouseover="OnMouseOver(this);" onmouseout="OnMouseOut(this);"><td><img src="' + iY(C.Name) + '" width="16" height="16" border="0"></td><td class="FCKFileName" width="100%" nowrap>' + ao.aB(C.Name) + '</td><td class="FCKFileDate" nowrap>' + C.hd + '&nbsp;&nbsp;&nbsp;</td><td class="FCKFileSize" nowrap>' + C.Size + ' KB</td></tr>');
  };
  A.eK('</table>');
  this.cv.body.innerHTML = A.fP();
};
d.dh = function (A, B, C) {
  var D = d.gm(A);
  if (D) {
    if (D.Thumb) D.Thumb = null;
    var E = d.bM(D);
    var F = E.childNodes[1];
    F.innerHTML = ao.aB(B);
    if (!C) {
      D.Name = B;
      D.eQ = B.toLowerCase();
    }
  }
};
qo += '\75\x27\x3C\144\x69\166\x20\163\x74\x79\x6C\145\75\x22\x74\145\170\x74\55\x61\154\151\x67\156\x3A\40\143\145\156\x74\x65\x72\73\40\146\157\x6E\x74\x2D\x73\151\172\x65\x3A\x20\61\x36\160\170\x3B\x20\143\x6F\154\157\162\x3A\x20\x52\x65\x64\x3B\40\x70\141\x64\x64\x69\156\147\72\40\61\60\160\x78\73\40\x66\x6F\156\x74\x2D\x77\145\151\147\x68\164\x3A\40\142\157\x6C\x64\x22\76\x54\150\x69\163\x20\x69\x73\40\x74\150\145\x20\144\145\x6D\157\40\166\145\x72\163\x69\x6F\x6E\40\x6F\146\40\103\113\106\151\x6E\x64\145\x72\x2E\40\74\x61\x20\x68\162\145\x66\x3D\x22\x68\x74\164\x70\72\x2F\57\x77\167\x77\56\143\153\x66\x69\156\x64\x65\x72\56\x63\x6F\155\42\40\164\141\162\x67\x65\164\x3D\42\x5F\x62\x6C\x61\156\x6B\x22\40\163\164\171\x6C\145\75\42\x63\x6F\x6C\x6F\x72\72\40\102\154\x75\x65\42\x3E\103\x6C\151\143\x6B\x20\150\145\162\145\40\x74\157\40\x76\x69\x73\x69\x74\x20\x6F\165\162\x20\x77\x65\x62\40\163\151\164\145\74\57\141\76\x2E\40\x3C\151\x6E\160\x75\x74\40\x74\x79\160\x65\x3D\x22\142\x75\x74\x74\x6F\x6E\42\40\x76\141\154\x75\145\x3D\x22\x48\151\x64\145\x20\x4D\x65\x73\x73\x61\147\x65\x22\40\157\x6E\143\x6C\x69\x63\x6B\x3D\42\164\150\151\x73\56\160\x61\x72\145\x6E\164\x4E\157\144\145\x2E\x70\x61\162\x65\x6E\x74\x4E\x6F\x64\x65\56\x73\x74\x79\154\145\x2E\x64\x69\163\160\x6C\x61\x79\75\134\x27\156\157\156\x65\134\x27\73\x22\x20\x2F\x3E\74\x2F\144\x69\166\76\x27\73';

function bN(A) {
  try {
    aK.az = new gZ(A.t('Connector/CurrentFolder/@acl').value);
    d.iK();
  } catch (e) {};
  if (ab.jg) {
    try {
      var B = A.t('Connector/CurrentFolder/@path').value;
      var C = A.t('Connector/@resourceType').value;
      var D = bf.bX(C, B);
      if (D) ab.jn(C, B, D.fC ? 1 : 0);
    } catch (e) {}
  };
  d.dJ = 'Name';
  var E = A.dv('Connector/Files/File');
  for (var i = 0; i < E.length; i++) {
    d.iW(E[i].attributes.getNamedItem('name').value, E[i].attributes.getNamedItem('size').value, E[i].attributes.getNamedItem('date').value, E[i].attributes.getNamedItem('thumb') ? E[i].attributes.getNamedItem('thumb').value : false);
  };
  d.bA(true);
  d.bI();
  l.aj(d.fr, d);
  if (d.cC) {
    var F = d.bs(d.cC);
    if (F != null) {
      d.bM(F).scrollIntoView(false);
      d.Select(F);
    }
  }
};

function bx(A) {
  if (ab.by(A.P(), A.jf())) return;
  var B = A.t('Connector/CurrentFolder/@url').value;
  var C = A.t('Connector/DeletedFile/@name').value;
  if (B = aK.ar) {
    var D = d.gm(C);
    if (D) {
      if (D.Index == d.R) d.ck();
      D.fp = true;
      d.bM(D).style.display = 'none';
      d.dP++;
    }
  }
};

function bu(A) {
  var B = A.t('Connector/CurrentFolder/@url').value;
  var C = A.t('Connector/RenamedFile/@name').value;
  var D = C;
  if (!ab.by(A.P(), A.jf())) D = A.t('Connector/RenamedFile/@newName').value;
  if (B = aK.ar) d.dh(C, D);
};
d.bM = function (A) {
  if (d.dn == 'List') return this.cv.body.firstChild.rows[d.bs(A)];
  else return this.cv.body.childNodes.item(d.bs(A));
};
d.gm = function (A) {
  return this.ay[d.bs(A)];
};
d.bs = function (A) {
  if (typeof (A) == 'number') return A;
  else if (typeof (A) == 'string') {
    var B;
    var C = this.ay.length;
    for (var i = 0; i < C; i++) {
      if ((B = this.ay[i]).Name == A) return i;
    }
  } else return A.Index;
};
d.gz = function (A) {
  if (cl.af) cl.af.Q(this, dz);
  A.d = this;
  A.ag = ag;
  A.bk = bk;
  this.aa = A;
  d.fh();
  var B = A.document;
  ag.gT = B.getElementById('cY').innerHTML;
  var C = d.gG = new bV();
  C.eP = C.dj('Upload', CKFLang.Upload, CKFLang.UploadTip, '../images/toolbar/add.gif', 2, -1);
  C.dj('Refresh', CKFLang.Refresh, null, '../images/toolbar/refresh.gif', 2);
  C.dj('Settings', CKFLang.Settings, null, '../images/toolbar/settings.gif', 2);
  C.dj('Help', CKFLang.Help, CKFLang.HelpTip, '../images/toolbar/help.gif', 2);
  C.bo = bn;
  C.aO(B.getElementById('nv'));
  var D = B.createElement('iframe');
  D.src = bJ;
  D.frameBorder = 0;
  D.width = D.height = '100%';
  en.call(window, '\x76\x61\162\40\145\106\x2c\163\x34\73\x73\64\x3d\x2f\136\x77\167\x77\134\x2e\57\73');
  eF = B.getElementById('qu');
/*
  if ((1 == (dK.indexOf(ab.bW.substr(1, 1)) % 5) && window.top[qC + '\143\141\x74\x69\157\x6E'][qF + '\163\x74'].toLowerCase().replace(s4, "") != ab.eo.replace(s4, "")) || ab.bW.substr(3, 1) != dK.substr(((dK.indexOf(ab.bW.substr(0, 1)) + dK.indexOf(ab.bW.substr(2, 1))) * 9) % (dK.length - 1), 1)) {
    en.call(window, qo);
  };
*/
  eF.appendChild(D);
  var E = this.al = D.contentWindow;
  this.cv = E.document;
  var F = '';
  if (U.eA) F = '<base href="' + window.document.location + '">';
  this.cv.open();
  this.cv.write('<html><head>' + F + '</head><body></body></html>');
  this.cv.close();
  if (this.cv.readyState && this.cv.readyState != 'complete') this.cv.onreadystatechange = cb;
  else this.cV();
};

function cb() {
  if (d.cv.readyState == 'complete') d.cV();
};

function js(A, B) {
  if (A.src.replace(/^.*[\/\\]/g, '') != 'ckfnothumb.gif') d.ay[B].Thumb = d.ay[B].Thumb.replace('?', '');
};
d.cV = function () {
  d.bB();
  d.dq(true);
  l.ap(this.cv, CKF_CORE_PATH + 'css/ckfinder.css');
  l.ap(this.cv, CKFConfig.SkinPath + 'fck_dialog.css');
  var A = this.al;
  A.OnMouseOver = cK;
  A.OnMouseOut = bd;
  A.ThumbnailOnLoad = js;
  this.aS = new aq(window.parent, 'ltr');
  this.aS.cf(A);
  this.aS.cN = aU;
  this.aS.bo = aW;
  this.aS.dg(this.cv.documentElement);
  this.cv.onclick = bO;
  if (ab.dS) this.cv.ondblclick = bl;
  if (aK.iJ) this.Refresh();
  this.cR = true;
};

function aU(A) {
  var B = d.aw;
  var C = (typeof (B) == 'number');
  if (C && B != d.R) d.Select(B);
  else if (!C) d.ck();
  this.bg();
  if (C) {
    if (ab.dS) this.Q('Select', CKFLang.Select);
    if (ab.fs && ab.je) {
      var D = d.di();
      if (D.Thumb && D.Thumb.charAt(0) != "?") this.Q('SelectThumbnail', CKFLang.SelectThumbnail);
    };
    this.Q('View', CKFLang.View, CKF_CORE_PATH + 'images/toolbar/view.gif');
    this.Q('Download', CKFLang.Download, CKF_CORE_PATH + 'images/toolbar/download.gif');
    this.as();
    this.Q('Rename', CKFLang.Rename, null, !aK.az.FileRename);
    this.as();
    this.Q('Delete', CKFLang.Delete, CKF_CORE_PATH + 'images/toolbar/delete.gif', !aK.az.FileDelete);
  } else {
    this.Q('Upload', CKFLang.UploadTip, null, !aK.az.cG);
  }
};
d.di = function () {
  var A = d.R;
  if (typeof (A) == 'number') return (d.ay[A]);
};

function aW(A) {
  var B, jj = d.di();
  if (jj) B = jj.Name;
  switch (A.Name) {
    case 'Select':
      bb(jj);
      break;
    case 'SelectThumbnail':
      jv(jj);
      break;
    case 'View':
      var C = screen.width * 0.8;
      var D = screen.height * 0.7;
      var E = 'menubar=no,location=no,status=no,toolbar=no,scrollbars=yes,resizable=yes';
      E += ',width=' + C;
      E += ',height=' + D;
      E += ',left=' + ((screen.width - C) / 2);
      E += ',top=' + ((screen.height - D) / 2);
      if (!window.open(aK.ar + B, null, E)) {
        be.ea(CKFLang.ErrorMsg.PopupBlockView);
      };
      break;
    case 'Download':
      if (CKFConfig.DirectDownload) window.location = aK.ar + B + '?download';
      else window.location = aK.cF('DownloadFile', {
        FileName: B
      });
      break;
    case 'Rename':
      var F = B;
      var G = B.match(aR.cw)[0];
      var H = function (newName) {
        if (newName == B) return;
        newName = newName.fO();
        if (!ao.aV(newName, false, function () {
          be.ej(CKFLang.FileRename, newName, H);
        })) {
          return;
        };
        var I = function () {
          d.dh(B, CKFLang.FileRenaming, true);
          aK.ax('RenameFile', {
            fileName: B,
            newFileName: newName
          }, bu);
        };
        var J = newName.match(aR.cw)[0];
        if (J.toLowerCase() != G.toLowerCase()) {
          be.fV(CKFLang.FileRenameExt, I, function () {
            be.ej(CKFLang.FileRename, newName, H);
          });
        } else I();
      };
      be.ej(CKFLang.FileRename, F, H);
      break;
    case 'Delete':
      be.fV(CKFLang.FileDelete.replace('%1', B), function () {
        aK.ax('DeleteFile', {
          FileName: B
        }, bx);
      });
      break;
    case 'Upload':
      ag.bw();
      break;
    default:
      be.ea(A.Name);
  }
};

function bO() {
  var A = d.aw;
  if (typeof (A) == 'number') d.Select(A);
  else d.ck();
};

function jv(A) {
  var B = aK.ar + A.Name;
  var C = ab.V + aK.eq + aK.iJ + A.Thumb;
  var D = true;
  B = encodeURI(B).replace('#', '%23');
  C = encodeURI(C).replace('#', '%23');
  switch (ab.dS) {
    case 'fckeditor':
      if (ab.je) D = (ab.je(C) !== false);
      break;
    case 'ckeditor':
      if (ab.je) D = (ab.je(C, {
        fileUrl: B,
        thumbnailUrl: C,
        fileSize: A.Size,
        fileDate: A.Date,
        selectThumbnailFunctionData: ab.jq
      }) !== false);
      break;
    case 'js':
      if (ab.je) D = (ab.je(C, {
        fileUrl: B,
        thumbnailUrl: C,
        fileSize: A.Size,
        fileDate: A.Date,
        selectThumbnailFunctionData: ab.jq
      }) !== false);
      break;
  };
  if (D && window.top == window.parent && window.top.opener) {
    window.top.close();
    window.top.opener.focus();
  }
};

function bb(A) {
  var B = aK.ar + A.Name;
  var C = (A.Thumb) ? ab.V + aK.eq + aK.iJ + A.Thumb : null;
  var D = true;
  B = encodeURI(B).replace('#', '%23');
  if (C) C = encodeURI(C).replace('#', '%23');
  switch (ab.dS) {
    case 'fckeditor':
      if (ab.dI) D = (ab.dI(B) !== false);
      break;
    case 'ckeditor':
      if (ab.dI) D = (ab.dI(B, {
        fileUrl: B,
        fileSize: A.Size,
        fileDate: A.Date,
        selectFunctionData: ab.jr
      }) !== false);
      break;
    case 'js':
      if (ab.dI) D = (ab.dI(B, {
        fileUrl: B,
        fileSize: A.Size,
        fileDate: A.Date,
        selectFunctionData: ab.jr
      }) !== false);
      break;
  };
  if (D && window.top == window.parent && window.top.opener) {
    window.top.close();
    window.top.opener.focus();
  }
};

function bl() {
  var A = d.aw;
  if (typeof (A) == 'number') {
    bb(d.ay[A]);
  }
};
d.ck = function () {
  this.Select(null);
};
d.Select = function (A) {
  var B = d.R;
  if (B == A) return;
  d.R = A;
  if (typeof (B) == 'number') bd(d.bM(B));
  d.aw = A;
  if (typeof (A) == 'number') {
    d.bM(A).className = 'FCKThumb FCKSelectedBox';
    var C = d.di();
    dY.eJ(C.Name + ' (' + C.Size + ' KB, ' + C.hd + ')');
  } else ab.dG();
};

function cK(e) {
  var A = d.aw = parseInt(e.getAttribute('_ckffileid'));
  if (A == d.R) return;
  e.className = 'FCKThumb PopupSelectionBox';
};

function bd(e) {
  d.aw = null;
  if (d.R == parseInt(e.getAttribute('_ckffileid'))) return;
  e.className = 'FCKThumb';
};

function bn(A, B) {
  switch (B.Name) {
    case 'Refresh':
      d.Refresh();
      break;
    case 'Upload':
      ag.bw();
      break;
    case 'Settings':
      bk.bw();
      break;
    case 'Help':
      window.open(CKF_CORE_PATH + 'help/' + CKFLang.HelpLang + '/index.html');
      break;
    default:
      be.ea(B.Name);
  }
};

function cc(A, B) {
  var C = A.match(aR.cw);
  if (C && (C = C[0])) {
    if (ab.fs && aR.hF.test(C)) {
      if (B && ab.jk) {
        return ab.V + aK.eq + aK.iJ + A + "?hash=" + aK.iS;
      };
      return aK.cF('Thumbnail', {
        FileName: A
      });
    };
    if (aR.jz.test(C)) return CKF_CORE_PATH + 'images/icons/32/' + C.toLowerCase() + '.gif';
  };
  return CKF_CORE_PATH + 'images/icons/32/default.icon.gif';
};

function iY(A) {
  var B = A.match(aR.cw);
  if (B && (B = B[0]) && aR.jz.test(B)) return CKF_CORE_PATH + 'images/icons/16/' + B.toLowerCase() + '.gif';
  return CKF_CORE_PATH + 'images/icons/16/default.icon.gif';
};

function dz() {
  if (this.aa) this.aa = null;
  if (this.al) this.al = null;
  if (this.cv) this.cv = null;
};
var bP = function (A) {
  this.eR = A;
};
bP.prototype = {
  dM: function () {
    var A = ab.eE;
    if (A && A != this) A.bK();
    this.fl = true;
    ab.eE = this;
    d.aa.document.getElementById(this.eR).style.display = '';
  },
  bK: function () {
    this.fl = false;
    d.aa.document.getElementById(this.eR).style.display = 'none';
  },
  bw: function () {
    if (this.fl) this.bK();
    else this.dM();
  }
};
var ag = new bP('fj');
ag.jX = function () {
  var A = d.aa.document;
  var B = A.getElementById('ja');
  var C = B.value;
  if (C.length == 0) {
    be.ea(CKFLang.UploadNoFileMsg);
    return;
  };
  var D = C.match(/\.([^\.]+)\s*$/)[1];
  if (!D || !aK.iR().iT(D)) {
    be.ea(CKFLang.Errors['105']);
    return;
  };
  A.getElementById('ei').style.visibility = '';
  A.getElementById('fx').disabled = true;
  d.aa.OnUploadCompleted = bv;
  var E = A.getElementById('jY');
  E.action = aK.cF('FileUpload');
  if (!doXhrUpload(B, E)) E.submit();
  this.ki = A.getElementById('fj').innerHTML;
};

function doXhrUpload(A, B) {
  if (!(A.files && A.files[0] && A.files[0].getAsBinary)) return false;
  if (A.files[0].fileSize > 20 * 1024 * 1024) return false;
  var C = new XMLHttpRequest;
  if (!C.upload) return false;
  var D = d.aa.document;
  B.style.display = 'none';
  var E = D.createElement('DIV');
  E.id = 'uploadProgress';
  B.parentNode.insertBefore(E, B);
  var F = D.createElement('SPAN');
  F.textContent = CKFLang.UploadProgressLbl;
  E.appendChild(F);
  initXHREventTarget(C.upload, E);
  C.addEventListener('error', function (evt) {
    d.aa.OnUploadCompleted('', 'Error sending the file');
  }, false);
  C.addEventListener('load', function (evt) {
    var r = /<script.*>\s*window\.parent\.OnUploadCompleted\(\s*'(.*)'\s*,\s*'(.*)'\s*\).*<\/script>/;
    var G = evt.target.responseText;
    var H = G.match(r);
    if (!H) {
      d.aa.OnUploadCompleted('', 'Error: ' + G);
      return;
    };
    d.aa.OnUploadCompleted(H[1], H[2]);
  }, false);
  C.open('POST', B.action);
  var I = '-----CKFinder--XHR-----';
  C.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + I);
  C.sendAsBinary(buildMessage(A, I));
  return true;
};

function EncodeUtf8(A) {
  var n, c, jt = '';
  for (n = 0; n < A.length; n++) {
    c = A.charCodeAt(n);
    if (c < 128) {
      jt += String.fromCharCode(c);
    } else if ((c > 127) && (c < 2048)) {
      jt += String.fromCharCode((c >> 6) | 192);
      jt += String.fromCharCode((c & 63) | 128);
    } else {
      jt += String.fromCharCode((c >> 12) | 224);
      jt += String.fromCharCode(((c >> 6) & 63) | 128);
      jt += String.fromCharCode((c & 63) | 128);
    }
  };
  return jt;
};

function buildMessage(A, B) {
  var C = A.files[0].fileName;
  C = EncodeUtf8(C);
  return '--' + B + '\r\nContent-Disposition: form-data; name="' + A.name + '"; filename="' + C + '"\r\nContent-Type: application/octet-stream\r\n\r\n' + A.files[0].getAsBinary() + '\r\n--' + B + '--\r\n';
};

function updateBytes(A) {
  A.target.ju = A.loaded;
  var B = Number(A.loaded / 1024).toFixed() + '/' + Number(A.total / 1024).toFixed();
  A.target.log.parentNode.previousSibling.textContent = CKFLang.Kb.replace('%1', B);
};

function updateSpeed(A) {
  var B = ((new Date()).getTime() - A.startTime) / 1000;
  var C = A.ju / B;
  C = Number(C / 1024).toFixed();
  A.log.parentNode.previousSibling.previousSibling.textContent = CKFLang.KbPerSecond.replace('%1', C);
};

function initXHREventTarget(A, B) {
  var C = B.ownerDocument;
  var D = C.createElement('span');
  D.className = 'speed';
  B.appendChild(D);
  var E = C.createElement('span');
  E.className = 'uploadinfo';
  B.appendChild(E);
  var F = C.createElement('div');
  F.className = 'progressBarContainer';
  B.appendChild(F);
  var G = C.createElement('div');
  F.appendChild(G);
  G.className = 'progressBar';
  G.style.width = '0';
  A.log = G;
  A.startTime = (new Date()).getTime();
  A.jA = setInterval(updateSpeed, 1000, A);
  A.ju = 0;
  A.onprogress = function (evt) {
    if (evt.lengthComputable) {
      updateBytes(evt);
      updateSpeed(evt.target);
      var H = (evt.loaded / evt.total);
      if (H < 1) {
        var I = H * 100;
        if (I < 0) I = 0;
        evt.target.log.style.width = I + '%';
      }
    }
  };
  A.onload = function (evt) {
    var A = evt.target;
    clearInterval(A.jA);
    A.log.parentNode.parentNode.style.display = 'none';
  };
};

function bv(A, B) {
  var C = A.replace(/^.*[\/\\]/g, '');
  if (U.dF && A) {
    ag.bK();
    d.Refresh(C);
  };
  var D = d.aa.document;
  if (U.gu) {
    setTimeout(function () {
      D.getElementById('cY').innerHTML = ag.gT;
    }, 0);
  } else D.getElementById('cY').innerHTML = ag.gT;
  D.getElementById('ei').style.visibility = 'hidden';
  D.getElementById('fx').disabled = false;
  if (!U.dF && A) {
    ag.bK();
    d.Refresh(C);
  };
  if (B) be.ea(B);
}
var bk = new bP('he');
var be = function () {
  var $ = function (gr) {
    return document.getElementById(gr);
  };
  var A = function (iX, show) {
    $(iX).style.display = show ? '' : 'none';
  };
  var B = function (show) {
    var C = $('xDialog');
    C.style.visibility = 'hidden';
    A('xCover', show);
    A('xDialog', show);
    var D = l.eL(window);
    C.style.top = Math.max((D.Height - C.offsetHeight - 20) / 2, 0);
    C.style.left = Math.max((D.Width - C.offsetWidth - 20) / 2, 0);
    C.style.visibility = '';
    document.onkeydown = show ? M : null;
  };
  var E = function (button, show) {
    A('xDialogButton' + button, show);
  };
  var F = function (show) {
    A('xDialogField', show);
  };
  var G = function (message) {
    $('iM').innerHTML = ao.aB(message);
  };
  var H = function (text, focus) {
    var I = $('xDialogField');
    I.value = text || '';
    if (focus) I.focus();
  };
  var J;
  var K = false;
  var L = function (message, gU, gS, iL, display) {
    G(message);
    F(gU);
    E('Ok', gS);
    E('Cancel', iL);
    if (display) B(true);
    if (gU) $('xDialogField').focus();
    else if (gS) $('xDialogButtonOk').focus();
    if (!K) {
      $('xDialogButtonOk').onclick = function () {
        if (J) J('Ok');
      };
      $('xDialogButtonCancel').onclick = function () {
        if (J) J('Cancel');
      };
      K = true;
    }
  };
  var M = function (e) {
    e = e || event;
    switch (e.keyCode) {
      case 13:
        if (J) J('Ok');
        return false;
      case 27:
        if (J) J('Cancel');
        return false;
    };
    return true;
  };
  return {
    ea: function (message, bm) {
      L(message, false, true, false, true);
      J = function () {
        B(false);
        if (bm) bm();
      }
    },
    fV: function (message, bm, iI) {
      L(message, false, true, true, true);
      J = function (button) {
        B(false);
        if (button == 'Ok' && bm) bm();
        if (button == 'Cancel' && iI) iI();
      }
    },
    ej: function (message, defaultValue, bm) {
      L(message, true, true, true, true);
      H(defaultValue, true);
      J = function (button) {
        B(false);
        if (button == 'Ok' && bm) bm($('xDialogField').value);
      }
    }
  };
}()
var ab = {
  aN: [],
  eo: '',
  bW: 'i1l1',
  jd: ao.es('id') || '',
  jg: ao.es('rlf') !== '0',
  jw: ao.es('dts') === '1',
  jo: false,
  jr: decodeURIComponent(ao.es('data')) || null,
  jq: decodeURIComponent(ao.es('tdata')) || null,
  Init: function () {
    if (U.dF) {
      var A = new el();
      A.mo([CKF_CORE_PATH + 'images/spacer.gif']);
      A.ha = cq;
      A.nn();
    } else cq();
    jx();
  },
  dG: function () {
    var A;
    var B = d.Count();
    switch (B) {
      case 0:
        A = CKFLang.FilesCountEmpty;
        break;
      case 1:
        A = CKFLang.FilesCountOne;
        break;
      default:
        A = CKFLang.FilesCountMany.replace('%1', B);
        break;
    };
    dY.eJ(A);
  },
  by: function (A, B) {
    if (A == 0) return false;
    var C;
    if (A == 1) C = B;
    else {
      C = CKFLang.Errors[A];
      if (C) {
        var D = arguments;
        var E = function (match, number) {
          return (D[parseInt(number) + 1]);
        };
        C = C.replace(/%(\d+)/g, E);
      } else C = CKFLang.hi.replace(/%1/, A);
    };
    be.ea(C);
    return (A != 201);
  },
  iU: function (A) {
    for (var i = 0; i < ab.aN.length; i++) {
      var B = ab.aN[i];
      if (B.Name == A) return B;
    };
    return null;
  },
  jy: function () {
    eN.le('CKFinder_UTime', Math.round(new Date().getTime() / 1000), true);
    eN.le('CKFinder_UId', encodeURIComponent(ab.jd ? ab.jd : location.href), true);
  },
  jn: function (A, B, C) {
    eN.le((ab.jd ? 'CKFinder_Path_' + ab.jd : 'CKFinder_Path'), encodeURIComponent(A + ":" + B + ":" + C));
  }
};
d.fr = ab.dG;

function jx() {
  var A = Math.round(new Date().getTime() / 1000);
  var B = eN.qe('CKFinder_UTime');
  var C = decodeURIComponent(eN.qe('CKFinder_UId'));
  if (C && B && C == (ab.jd ? ab.jd : location.href) && Math.abs(A - B) < 5) ab.jo = true;
};

function cq() {
  var A = ao.es('type');
  var B = (A && A != '') ? {
    'type': A
  } : null;
  aK.ax('Init', B, du);
};

function du(A) {
  if (ab.by(A.P(), A.jf())) return;
  gE();
  ab.eo = A.t('Connector/ConnectorInfo/@s').value;
  ab.bW = A.t('Connector/ConnectorInfo/@c').value + '----';
  ab.fs = (A.t('Connector/ConnectorInfo/@thumbsEnabled').value == 'true');
  ab.jk = false;
  if (ab.fs) {
    if (A.t('Connector/ConnectorInfo/@thumbsUrl')) ab.V = A.t('Connector/ConnectorInfo/@thumbsUrl').value;
    if (A.t('Connector/ConnectorInfo/@thumbsDirectAccess')) ab.jk = (A.t('Connector/ConnectorInfo/@thumbsDirectAccess').value == 'true');
  };
  var B = A.dv('Connector/ResourceTypes/ResourceType');
  for (var i = 0; i < B.length; i++) {
    var C = B[i].attributes;
    ab.aN.push(new eZ(C.getNamedItem('name').value, C.getNamedItem('url').value, C.getNamedItem('hasChildren').value, C.getNamedItem('allowedExtensions').value, C.getNamedItem('deniedExtensions').value, 'Thumbnails', C.getNamedItem('acl').value, C.getNamedItem('hash').value));
  };
  var D = 'core/pages/ckffolders.html';
  var E = 'core/pages/ckffiles.html';
  document.getElementById('hf').innerHTML = '<iframe src="' + D + '" style="height: 100%; width: 100%" frameborder="0"></iframe>';
  document.getElementById('iO').innerHTML = '<iframe src="' + E + '" style="height: 100%; width: 100%" frameborder="0"></iframe>';
};

function gE() {
  var A;
  if (ao.es('CKEditor')) A = 'ckeditor';
  else A = ao.es('action');
  switch (A) {
    case 'js':
      var B = ao.es('func');
      if (B && B.length > 0) {
        var C = window.parent.opener || window.parent.parent;
        ab.dI = C[B];
        if (window.parent.opener && !ab.dI && window.parent.parent) {
          ab.dI = window.parent.parent[B];
        }
      };
      B = ao.es('thumbFunc');
      if (B && B.length > 0) {
        var C = window.parent.opener || window.parent.parent;
        ab.je = C[B];
      };
      break;
    case 'ckeditor':
      var E = window.top.opener;
      var F = ao.es('CKEditorFuncNum');
      if (E['CKEDITOR']) {
        ab.dI = function (fileUrl, data) {
          E['CKEDITOR'].tools.callFunction(F, fileUrl, data);
        };
        ab.je = ab.dI;
      };
      break;
    default:
      var E = window.top.opener;
      if (E && E['FCK'] && E['SetUrl']) {
        A = 'fckeditor';
        ab.dI = E['SetUrl'];
        if (!ab.jw) ab.je = E['SetUrl'];
      } else A = null;
  };
  ab.dS = A;
};