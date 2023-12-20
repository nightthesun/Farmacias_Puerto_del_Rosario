/*
para crear comandos en laravel ejemplo para crear base de datos nueva
// Referencia: https://stackoverflow.com/questions/38832166/how-to-create-a-mysql-db-with-laravel#answer-47316035  

*/

import { data } from "jquery";

/*para el biometrico instalar el composer zklib
composer require rats/zkteco
https://github.com/raihanafroz/zkteco

*/

/*
PARA LEER TEXTOS
https://github.com/js1016/txt-reader

para leer excel
https://sheetjs.com/
ejemplo:
https://www.aspsnippets.com/Articles/Read-Parse-Excel-File-XLS-and-XLSX-using-JavaScript.aspx

!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(r,o,function(e){return t[e]}.bind(null,o));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=7)}([function(t,e){var n;n=function(){return this}();try{n=n||new Function("return this")()}catch(t){"object"==typeof window&&(n=window)}t.exports=n},function(t,e,n){"use strict";e.a=function(t){var e=this.constructor;return this.then((function(n){return e.resolve(t()).then((function(){return n}))}),(function(n){return e.resolve(t()).then((function(){return e.reject(n)}))}))}},function(t,e,n){(function(n){var r,o,i;o=[],void 0===(i="function"==typeof(r=function(){"use strict";var t=void 0!==n?n:self;if(void 0!==t.TextEncoder&&void 0!==t.TextDecoder)return{TextEncoder:t.TextEncoder,TextDecoder:t.TextDecoder};var e=["utf8","utf-8","unicode-1-1-utf-8"];return{TextEncoder:function(t){if(e.indexOf(t)<0&&null!=t)throw new RangeError("Invalid encoding type. Only utf-8 is supported");this.encoding="utf-8",this.encode=function(t){if("string"!=typeof t)throw new TypeError("passed argument must be of type string");var e=unescape(encodeURIComponent(t)),n=new Uint8Array(e.length);return e.split("").forEach((function(t,e){n[e]=t.charCodeAt(0)})),n}},TextDecoder:function(t,n){if(e.indexOf(t)<0&&null!=t)throw new RangeError("Invalid encoding type. Only utf-8 is supported");if(this.encoding="utf-8",this.ignoreBOM=!1,this.fatal=void 0!==n&&"fatal"in n&&n.fatal,"boolean"!=typeof this.fatal)throw new TypeError("fatal flag must be boolean");this.decode=function(t,e){if(void 0===t)return"";if("boolean"!=typeof(void 0!==e&&"stream"in e&&e.stream))throw new TypeError("stream option must be boolean");if(ArrayBuffer.isView(t)){var n=new Uint8Array(t.buffer),r=new Array(n.length);return n.forEach((function(t,e){r[e]=String.fromCharCode(t)})),decodeURIComponent(escape(r.join("")))}throw new TypeError("passed argument must be an array buffer view")}}}})?r.apply(e,o):r)||(t.exports=i)}).call(this,n(0))},function(t,e,n){"use strict";void 0===Uint8Array.prototype.indexOf&&(Uint8Array.prototype.indexOf=function(t){return Array.prototype.indexOf.call(this,t)}),void 0===Uint8Array.prototype.slice&&(Uint8Array.prototype.slice=function(t,e){return new Uint8Array(Array.prototype.slice.call(this,t,e))}),Uint8Array.prototype.forEach||(Uint8Array.prototype.forEach=function(t){var e,n;if(null==this)throw new TypeError("this is null or not defined");var r=Object(this),o=r.length>>>0;if("function"!=typeof t)throw new TypeError(t+" is not a function");for(arguments.length>1&&(e=arguments[1]),n=0;n<o;){var i;n in r&&(i=r[n],t.call(e,i,n,r)),n++}})},function(t,e,n){"use strict";var r=this&&this.__importDefault||function(t){return t&&t.__esModule?t:{default:t}};e.__esModule=!0;var o=n(2);n(8),n(3);var i,u=r(n(12)),a=function(t,e){this.action=t,this.data=void 0!==e?e:null};!function(t){t[t.Initialized=0]="Initialized",t[t.Queued=1]="Queued",t[t.Running=2]="Running",t[t.Completed=3]="Completed"}(i||(i={}));var s=function(){function t(t,e,n){var r=this;this.id=t,this.requestMessage=e,this.parser=n,this.requestMessage.taskId=t,this.state=i.Initialized,this.onProgress=null,this.startTime=0,this.promise=new Promise((function(t,e){r.resolve=t,r.reject=e}))}return t.prototype.dispose=function(){this.resolve=null,this.reject=null,this.promise=null},t.prototype.run=function(){this.state=i.Running,this.startTime=(new Date).getTime()},t.prototype.complete=function(t){this.state=i.Completed;var e=(new Date).getTime()-this.startTime;if(t.success){var n={timeTaken:e,message:t.message,result:t.result};this.resolve(n)}else this.reject(t.message);this.dispose()},t.prototype.updateProgress=function(t){null!==this.onProgress&&this.onProgress.call(this.parser,t)},t.prototype.then=function(t){var e=this;return this.promise&&this.promise.then((function(n){t.call(e.parser,n)})).catch((function(t){})),this},t.prototype.catch=function(t){var e=this;return this.promise&&this.promise.catch((function(n){t.call(e.parser,n)})),this},t.prototype.progress=function(t){return this.onProgress=t,this},t}(),c=function(){function t(){var t=this;this.taskList=[],this.runningTask=null,this.queuedTaskList=[],this.verboseLogging=!1,this.utf8decoder=new o.TextDecoder("utf-8"),this.lineCount=0,Object.defineProperty(this,"lineCount",{value:0,writable:!1}),this.file=null,this.worker=new Worker(window.URL.createObjectURL(new Blob(["!function(e){var t={};function r(i){if(t[i])return t[i].exports;var n=t[i]={i:i,l:!1,exports:{}};return e[i].call(n.exports,n,n.exports,r),n.l=!0,n.exports}r.m=e,r.c=t,r.d=function(e,t,i){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},r.r=function(e){\"undefined\"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:\"Module\"}),Object.defineProperty(e,\"__esModule\",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&\"object\"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(r.r(i),Object.defineProperty(i,\"default\",{enumerable:!0,value:e}),2&t&&\"string\"!=typeof e)for(var n in e)r.d(i,n,function(t){return e[t]}.bind(null,n));return i},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,\"a\",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p=\"\",r(r.s=6)}([function(e,t){var r;r=function(){return this}();try{r=r||new Function(\"return this\")()}catch(e){\"object\"==typeof window&&(r=window)}e.exports=r},,function(e,t,r){(function(r){var i,n,s;n=[],void 0===(s=\"function\"==typeof(i=function(){\"use strict\";var e=void 0!==r?r:self;if(void 0!==e.TextEncoder&&void 0!==e.TextDecoder)return{TextEncoder:e.TextEncoder,TextDecoder:e.TextDecoder};var t=[\"utf8\",\"utf-8\",\"unicode-1-1-utf-8\"];return{TextEncoder:function(e){if(t.indexOf(e)<0&&null!=e)throw new RangeError(\"Invalid encoding type. Only utf-8 is supported\");this.encoding=\"utf-8\",this.encode=function(e){if(\"string\"!=typeof e)throw new TypeError(\"passed argument must be of type string\");var t=unescape(encodeURIComponent(e)),r=new Uint8Array(t.length);return t.split(\"\").forEach((function(e,t){r[t]=e.charCodeAt(0)})),r}},TextDecoder:function(e,r){if(t.indexOf(e)<0&&null!=e)throw new RangeError(\"Invalid encoding type. Only utf-8 is supported\");if(this.encoding=\"utf-8\",this.ignoreBOM=!1,this.fatal=void 0!==r&&\"fatal\"in r&&r.fatal,\"boolean\"!=typeof this.fatal)throw new TypeError(\"fatal flag must be boolean\");this.decode=function(e,t){if(void 0===e)return\"\";if(\"boolean\"!=typeof(void 0!==t&&\"stream\"in t&&t.stream))throw new TypeError(\"stream option must be boolean\");if(ArrayBuffer.isView(e)){var r=new Uint8Array(e.buffer),i=new Array(r.length);return r.forEach((function(e,t){i[t]=String.fromCharCode(e)})),decodeURIComponent(escape(i.join(\"\")))}throw new TypeError(\"passed argument must be an array buffer view\")}}}})?i.apply(t,n):i)||(e.exports=s)}).call(this,r(0))},function(e,t,r){\"use strict\";void 0===Uint8Array.prototype.indexOf&&(Uint8Array.prototype.indexOf=function(e){return Array.prototype.indexOf.call(this,e)}),void 0===Uint8Array.prototype.slice&&(Uint8Array.prototype.slice=function(e,t){return new Uint8Array(Array.prototype.slice.call(this,e,t))}),Uint8Array.prototype.forEach||(Uint8Array.prototype.forEach=function(e){var t,r;if(null==this)throw new TypeError(\"this is null or not defined\");var i=Object(this),n=i.length>>>0;if(\"function\"!=typeof e)throw new TypeError(e+\" is not a function\");for(arguments.length>1&&(t=arguments[1]),r=0;r<n;){var s;r in i&&(s=i[r],e.call(t,s,r,i)),r++}})},,,function(module,exports,__webpack_require__){\"use strict\";exports.__esModule=!0;var text_encoding_shim_1=__webpack_require__(2);__webpack_require__(3);var utf8decoder=new text_encoding_shim_1.TextDecoder(\"utf-8\"),DEFAULT_CHUNK_SIZE=104857600,currentTaskId=null,txtReaderWorker=null,sniffWorker=null,verboseLogging=!1,useTransferrable=navigator.userAgent.indexOf(\"Firefox\")>-1,respondMessage=function(e){e.done&&(currentTaskId=null),postMessage.apply(null,[e])},respondTransferrableMessage=function(e,t){e.done&&(currentTaskId=null),postMessage(e,t)},createProgressResponseMessage=function(e){var t=new ResponseMessage(e);return t.done=!1,t},validateWorker=function(){return null!==txtReaderWorker||(respondMessage(new ResponseMessage(!1,\"File has not been loaded into the worker, need to loadFile first.\")),!1)},mergeUint8Array=function(e,t){if(0===e.byteLength)return t;if(0===t.byteLength)return e;var r=new Uint8Array(e.byteLength+t.byteLength);return r.set(e,0),r.set(t,e.byteLength),r},isLineWithinLinesRange=function(e,t){var r=getLinesRangeEnd(t);return e>=t.start&&e<=r},getLinesRangeEnd=function(e){return void 0!==e.count?e.start+e.count-1:e.end},getLinesRangeCount=function(e){return void 0!==e.count?e.count:e.end-e.start+1},getLinesRangeStart=function(e){return\"number\"==typeof e?e:e.start},getStartCountForSporadicLineItem=function(e){return\"number\"==typeof e?{start:e,count:1}:void 0!==e.end?{start:e.start,count:e.end-e.start+1}:e};self.addEventListener(\"message\",(function(e){var t=e.data;if(verboseLogging&&console.log(\"Worker thread received a message from main thread: \\r\\n\",e.data),null!==currentTaskId)throw\"The worker thread is busy.\";switch(currentTaskId=t.taskId,t.action){case\"loadFile\":txtReaderWorker=new TxtReaderWorker(t.data.file,t.data.config);break;case\"enableVerbose\":verboseLogging=!0,respondMessage(new ResponseMessage(!0));break;case\"setChunkSize\":if(\"number\"!=typeof t.data||t.data<1)return void respondMessage(new ResponseMessage(!1,\"Invalid CHUNK_SIZE, must be greater than 1.\"));txtReaderWorker&&(txtReaderWorker.CHUNK_SIZE=t.data),DEFAULT_CHUNK_SIZE=t.data,respondMessage(new ResponseMessage(DEFAULT_CHUNK_SIZE));break;case\"getLines\":validateWorker()&&txtReaderWorker.getLines(t.data.start,t.data.count);break;case\"getSporadicLines\":validateWorker()&&txtReaderWorker.getSporadicLines(t.data.sporadicLinesMap,t.data.decode);break;case\"iterateLines\":validateWorker()&&txtReaderWorker.iterateLines(t.data);break;case\"iterateSporadicLines\":validateWorker()&&txtReaderWorker.iterateSporadicLines(t.data.config,t.data.lines);break;case\"sniffLines\":sniffWorker=new TxtReaderWorker(t.data.file,void 0,{lineNumber:t.data.lineNumber,decode:t.data.decode})}}));var ResponseMessage=function(e,t,r){if(this.taskId=currentTaskId,1===arguments.length)this.success=!0,this.message=\"\",this.result=e;else if(2===arguments.length)switch(typeof e){case\"boolean\":this.success=e,this.message=t,this.result=null;break;default:this.success=!0,this.message=e,this.result=t}else this.success=e,this.message=t,this.result=r;this.done=!0},Iterator=function(){function e(){this.lineView=new Uint8Array(0),this.lastViewEndsWithCR=!1,this.onEachLine=null,this.eachLineScope=null,this.offset=0,this.createMap=!1,this.endOffset=0,this.linesToIterate=0,this.linesProcessed=0,this.currentLineNumber=1,this.startLineNumber=0,this.lastProgress=null,this.processedViewLength=0,this.lineBreakLength=0,this.map=[],this.lastMappedProgress=null,this.sporadicProcessed=0,this.sporadicLinesMap=[],this.isSporadicIterate=!1}return e.prototype.shouldBreak=function(){return!(!this.isPartialIterate()||this.linesProcessed!==this.linesToIterate&&this.sporadicProcessed!==this.linesToIterate)},e.prototype.isPartialIterate=function(){return this.linesToIterate>0},e.prototype.hitLine=function(e){var t=this.isPartialIterate(),r=0;if(!t||t&&this.currentLineNumber>=this.startLineNumber&&!this.isSporadicIterate||this.isSporadicIterate){var i=!1;if(t)if(this.isSporadicIterate){r=Math.round(this.sporadicProcessed/this.linesToIterate*1e4)/100;var n=this.sporadicLinesMap[0];\"number\"==typeof n?n===this.currentLineNumber&&(this.sporadicLinesMap.splice(0,1),i=!0):void 0!==n.count?this.currentLineNumber>=n.start&&this.currentLineNumber<=n.start+n.count-1&&(1===n.count?this.sporadicLinesMap.splice(0,1):(n.start++,n.count--),i=!0):this.currentLineNumber>=n.start&&this.currentLineNumber<=n.end&&(n.start===n.end?this.sporadicLinesMap.splice(0,1):n.start++,i=!0),i&&this.sporadicProcessed++}else this.linesProcessed++,i=!0,r=Math.round(this.linesProcessed/this.linesToIterate*1e4)/100;else if(i=!0,this.linesProcessed++,r=Math.round(this.processedViewLength/this.endOffset*1e4)/100,this.createMap){var s=Math.round(r);(null===this.lastMappedProgress||this.lastMappedProgress<s)&&(this.lastMappedProgress=s,this.map.push({line:this.currentLineNumber,offset:this.processedViewLength}))}this.onEachLineInternal&&i&&(e.buffer.byteLength!=e.byteLength&&(e=new Uint8Array(e)),this.onEachLineInternal.call(this,e)),null!==this.onEachLine&&i&&(e.buffer.byteLength!=e.byteLength&&(e=new Uint8Array(e)),this.onEachLine.call(this.eachLineScope,e,r,this.currentLineNumber))}t&&this.shouldReportProgress(r)&&(this.lastProgress=r,respondMessage(createProgressResponseMessage(r<100?r:100))),this.processedViewLength+=e.length+this.lineBreakLength,this.currentLineNumber++},e.prototype.shouldReportProgress=function(e){return null===this.lastProgress||e-this.lastProgress>5},e.prototype.bindEachLineFromConfig=function(e){this.onEachLine=new Function(\"return \"+e.eachLine)(),null===this.eachLineScope&&(this.eachLineScope=e.scope),this.eachLineScope.decode=function(e){return utf8decoder.decode(e)}},e}(),TxtReaderWorker=function(){function TxtReaderWorker(e,t,r){var i=this;if(\"[object file]\"===Object.prototype.toString.call(e).toLowerCase()){if(this.CHUNK_SIZE=DEFAULT_CHUNK_SIZE,this.file=e,this.lineCount=0,this.quickSearchMap=[],this.fr=new FileReader,this.fr.onload=function(){for(var e=new Uint8Array(i.fr.result),t=i.iterator;e.length>0;){var r=e.indexOf(13),n=e.indexOf(10),s=void 0,o=!1,a=!1;if(r>0&&(-1===n||r<n))r<e.length-1?10===e[r+1]?(s=r,t.lineBreakLength=2):(s=r,t.lineBreakLength=1):(a=!0,o=!0);else if(0===r)e.length>1?10===e[1]?(s=r,t.lineBreakLength=2):(s=r,t.lineBreakLength=1):(a=!0,o=!0);else if(n>0)s=n,t.lineBreakLength=1;else{if(0===n){if(t.lineBreakLength=t.lastViewEndsWithCR?2:1,t.hitLine(t.lastViewEndsWithCR?t.lineView.slice(0,t.lineView.length-1):t.lineView),t.shouldBreak())break;t.lineView=new Uint8Array(0),e=new Uint8Array(i.fr.result,1+e.byteOffset),t.lastViewEndsWithCR=!1;continue}o=!0}if(o){if(t.lastViewEndsWithCR){if(t.lineBreakLength=1,t.hitLine(t.lineView.slice(0,t.lineView.length-1)),t.shouldBreak())break;t.lineView=new Uint8Array(0)}t.lineView=mergeUint8Array(t.lineView,e),t.lastViewEndsWithCR=a;break}if(t.lastViewEndsWithCR){if(t.hitLine(t.lineView.slice(0,t.lineView.length-1)),t.shouldBreak())break;if(t.hitLine(e.slice(0,s)),t.shouldBreak())break;t.lastViewEndsWithCR=!1}else if(t.lineView=mergeUint8Array(t.lineView,e.slice(0,s)),t.hitLine(t.lineView),t.shouldBreak())break;t.lineView=new Uint8Array(0),e=new Uint8Array(i.fr.result,e.byteOffset+s+t.lineBreakLength)}if(!t.isPartialIterate()){var c=Math.round(t.offset/i.file.size*1e4)/100;respondMessage(createProgressResponseMessage(c<100?c:100))}t.lineBreakLength=0,i.iterator.isSporadicIterate?i.seekSporadic():(t.offset+=i.CHUNK_SIZE,i.seek())},this.iterator=new Iterator,this.iterator.createMap=!0,r){if(r.lineNumber<1)return void respondMessage(new ResponseMessage(!1,\"Sniff line number is invalid.\"));this.sniffLines=[],this.iterator.linesToIterate=r.lineNumber,this.iterator.createMap=!1}this.iterator.endOffset=this.file.size,t&&this.iterator.bindEachLineFromConfig(this.stringToFunction(t)),this.iterateLinesInternal((function(e){r?i.sniffLines.push(r.decode?utf8decoder.decode(e):e):i.lineCount++}),(function(){if(r)respondMessage(new ResponseMessage(i.sniffLines)),i.sniffLines=[],delete i.sniffLines,sniffWorker=null;else{i.quickSearchMap=i.iterator.map;var e={lineCount:i.lineCount};t&&(e.scope=i.removeFunctionsFromObject(i.iterator.eachLineScope)),respondMessage(new ResponseMessage(e))}}))}else respondMessage(new ResponseMessage(!1,\"Invalid file object\"))}return TxtReaderWorker.prototype.setPartialIterator=function(e,t){if(e<1||e>this.lineCount)return respondMessage(new ResponseMessage(!1,\"Start line number is invalid\")),!1;if(t<1)return respondMessage(new ResponseMessage(!1,\"Count is invalid\")),!1;var r=e+t-1;r=r>this.lineCount?this.lineCount:r;for(var i=0;i<this.quickSearchMap.length;i++)e>=this.quickSearchMap[i].line&&(i===this.quickSearchMap.length-1||e<this.quickSearchMap[i+1].line)&&(this.iterator.offset=this.quickSearchMap[i].offset,this.iterator.currentLineNumber=this.quickSearchMap[i].line),r<this.quickSearchMap[i].line&&(0===i||r>=this.quickSearchMap[i-1].line)?this.iterator.endOffset=this.quickSearchMap[i].offset:this.iterator.endOffset=this.file.size;return this.iterator.linesToIterate=t,this.iterator.startLineNumber=e,!0},TxtReaderWorker.prototype.iterateLinesInternal=function(e,t){this.iterator.onEachLineInternal=e,t&&(this.iterator.onSeekComplete=t),this.seek()},TxtReaderWorker.prototype._iterateLines=function(e,t,r,i){var n=this;if(this.iterator.offset=0,this.iterator.endOffset=this.file.size,this.iterator.bindEachLineFromConfig(e),this.iterator.onSeekComplete=function(){this.eachLineScope=n.removeFunctionsFromObject(this.eachLineScope),i()},null!==t&&null!==r){if(!this.setPartialIterator(t,r))return;this.seek()}else this.seek()},TxtReaderWorker.prototype.removeFunctionsFromObject=function(e){if(\"object\"==typeof e)for(var t in e)\"function\"==typeof e[t]?delete e[t]:\"object\"==typeof e[t]&&this.removeFunctionsFromObject(e[t]);return e},TxtReaderWorker.prototype.stringToFunction=function(config){var functionMap=config.functionMap;if(functionMap.length)for(var i=0;i<functionMap.length;i++){var functionString=eval(\"config.scope\"+functionMap[i]+\".toString()\");eval(\"config.scope\"+functionMap[i]+\"=\"+functionString)}return config},TxtReaderWorker.prototype.iterateLines=function(e){var t=this,r=this.stringToFunction(e.config);this._iterateLines(r,e.start,e.count,(function(){respondMessage(new ResponseMessage(t.iterator.eachLineScope))}))},TxtReaderWorker.prototype.setSporadicIterator=function(e){e=this.sortAndMergeLineMap(e);for(var t=0,r=0;r<e.length;r++)\"number\"==typeof e[r]?t++:t+=getLinesRangeCount(e[r]);this.iterator.linesToIterate=t,this.iterator.endOffset=this.file.size,this.iterator.isSporadicIterate=!0,this.iterator.sporadicLinesMap=e,this.seekSporadic()},TxtReaderWorker.prototype.getSporadicLines=function(e,t){e=this.sortAndMergeLineMap(e);var r=[];this.iterator.onEachLineInternal=function(e){r.push({lineNumber:this.currentLineNumber,value:t?utf8decoder.decode(e):e})},this.iterator.onSeekComplete=function(){respondMessage(new ResponseMessage(r))},this.setSporadicIterator(e)},TxtReaderWorker.prototype.iterateSporadicLines=function(e,t){t=this.sortAndMergeLineMap(t),e=this.stringToFunction(e);var r=this;this.iterator.bindEachLineFromConfig(e),this.iterator.onSeekComplete=function(){this.eachLineScope=r.removeFunctionsFromObject(this.eachLineScope),respondMessage(new ResponseMessage(this.eachLineScope))},this.setSporadicIterator(t)},TxtReaderWorker.prototype.seek=function(){var e=this.iterator.linesToIterate>0&&this.iterator.linesProcessed===this.iterator.linesToIterate;if(this.iterator.offset>=this.iterator.endOffset||e)respondMessage(createProgressResponseMessage(100)),this.iterator.lineView.byteLength&&!e&&(this.iterator.hitLine(this.iterator.lineView),this.iterator.lineView=new Uint8Array(0)),this.iterator.onSeekComplete&&this.iterator.onSeekComplete.call(this.iterator),this.iterator=new Iterator;else{var t=this.file.slice(this.iterator.offset,this.iterator.offset+this.CHUNK_SIZE);this.fr.readAsArrayBuffer(t)}},TxtReaderWorker.prototype.seekSporadic=function(){if(this.iterator.sporadicProcessed===this.iterator.linesToIterate)a.call(this);else{var e=this.iterator.sporadicLinesMap[0],t=getLinesRangeStart(e);if(this.iterator.lineView.byteLength&&this.iterator.currentLineNumber===t){if(this.iterator.offset+=this.CHUNK_SIZE,!(this.iterator.offset>=this.iterator.endOffset)){var r=this.file.slice(this.iterator.offset,this.iterator.offset+this.CHUNK_SIZE);return void this.fr.readAsArrayBuffer(r)}this.iterator.hitLine(this.iterator.lineView),this.iterator.lineView=new Uint8Array(0),this.iterator.sporadicProcessed===this.iterator.linesToIterate&&a.call(this)}else if(this.iterator.sporadicLinesMap.length>0){for(var i=0;i<this.quickSearchMap.length;i++){var n=this.quickSearchMap[i].line,s=i===this.quickSearchMap.length-1?this.lineCount:this.quickSearchMap[i+1].line-1;if(t>=n&&t<=s){var o=this.quickSearchMap[i].offset;this.iterator.offset=o;r=this.file.slice(o,o+this.CHUNK_SIZE);return this.iterator.currentLineNumber=this.quickSearchMap[i].line,void this.fr.readAsArrayBuffer(r)}}a.call(this)}}function a(){respondMessage(createProgressResponseMessage(100)),this.iterator.onSeekComplete&&this.iterator.onSeekComplete.call(this.iterator),this.iterator=new Iterator}},TxtReaderWorker.prototype._getLines=function(e,t,r){if(this.setPartialIterator(e,t)){var i=[],n=[];this.iterateLinesInternal((function(e){i.push(e),useTransferrable&&n.push(e.buffer)}),(function(){setTimeout((function(){r(i,n)}),0)}))}},TxtReaderWorker.prototype.getLines=function(e,t){this._getLines(e,t,(function(e,t){useTransferrable?respondTransferrableMessage(new ResponseMessage(e),t):respondMessage(new ResponseMessage(e))}))},TxtReaderWorker.prototype.sortAndMergeLineMap=function(e){e.sort((function(e,t){return\"number\"==typeof e&&\"number\"==typeof t?e>t?1:-1:\"number\"==typeof e&&\"object\"==typeof t?e>t.start?1:-1:\"object\"==typeof e&&\"number\"==typeof t?e.start>t?1:-1:\"object\"==typeof e&&\"object\"==typeof t?e.start>t.start?1:-1:0}));for(var t=1;t<e.length;t++){var r=e[t-1],i=e[t];if(\"number\"==typeof i&&\"number\"==typeof r)i===r?(e.splice(t,1),t--):i===r+1&&(e[t-1]={start:r,end:i},e.splice(t,1),t--);else if(\"number\"==typeof i&&\"object\"==typeof r){var n=getLinesRangeEnd(r);isLineWithinLinesRange(i,r)?(e.splice(t,1),t--):i===n+1&&(void 0!==r.count?r.count++:r.end=i,e.splice(t,1),t--)}else if(\"object\"==typeof i&&\"number\"==typeof r)isLineWithinLinesRange(r,i)&&(e.splice(t-1,1),t--);else if(\"object\"==typeof i&&\"object\"==typeof r){var s=getLinesRangeEnd(i);(n=getLinesRangeEnd(r))>=i.start&&n<=s?(i.start=r.start,void 0!==i.count&&(i.count=s-i.start+1),e.splice(t-1,1),t--):i.start>=r.start&&s<=n?(e.splice(t,1),t--):i.start===n+1&&(void 0!==r.count?r.count+=s-i.start+1:r.end=s,e.splice(t,1),t--)}}return e},TxtReaderWorker}()}]);"]))),this.worker.addEventListener("message",(function(e){if(t.verboseLogging&&console.log("Main thread received a message from worker thread: \r\n",e.data),null!==t.runningTask){var n=e.data;if(n.taskId!==t.runningTask.id)throw"Received task ID ("+n.taskId+") does not match the running task ID ("+t.runningTask.id+").";if(n.done)t.completeTask(n);else{if(!("[object number]"===Object.prototype.toString.call(n.result).toLowerCase()&&n.result>=0&&n.result<=100))throw"Unkown message type";t.runningTask.updateProgress(n.result)}}}),!1)}return t.prototype.sniffLines=function(t,e,n){return void 0===n&&(n=!0),this.newTask("sniffLines",{file:t,lineNumber:e,decode:n})},t.prototype.loadFile=function(t,e){var n=this;this.file=t,Object.defineProperty(this,"lineCount",{value:0,writable:!1});var r={file:t};return e&&(r.config=this.getItertorConfigMessage(u.default(e))),this.newTask("loadFile",r).then((function(t){Object.defineProperty(n,"lineCount",{value:t.result.lineCount,writable:!1})}))},t.prototype.setChunkSize=function(t){return this.newTask("setChunkSize",t)},t.prototype.enableVerbose=function(){return this.verboseLogging=!0,this.newTask("enableVerbose")},t.prototype.getLines=function(t,e,n){var r=this;return void 0===n&&(n=!0),this.newTask("getLines",{start:t,count:e}).then((function(t){for(var e=0;e<t.result.length;e++)n&&(t.result[e]=r.utf8decoder.decode(t.result[e]))}))},t.prototype.getSporadicLines=function(t,e){return void 0===e&&(e=!0),this.newTask("getSporadicLines",{sporadicLinesMap:t,decode:e})},t.prototype.iterateLines=function(t,e,n){return this.newTask("iterateLines",{config:this.getItertorConfigMessage(u.default(t)),start:void 0!==e?e:null,count:void 0!==n?n:null})},t.prototype.iterateSporadicLines=function(t,e){return this.newTask("iterateSporadicLines",{config:this.getItertorConfigMessage(u.default(t)),lines:e})},t.prototype.getItertorConfigMessage=function(t){var e=[];return{eachLine:t.eachLine.toString(),scope:function t(n,r){var o=r;if("object"==typeof n)for(var i in n){var u=o+'["'+i+'"]';"function"==typeof n[i]?(n[i]=n[i].toString(),e.push(u)):"object"==typeof n[i]&&(n[i]=t(n[i],u))}return n}(t.scope,"")||{},functionMap:e}},t.prototype.newTask=function(t,e){var n=new a(t,e),r=new s(this.newTaskId(),n,this);return this.taskList.push(r),this.runningTask?(this.queuedTaskList.push(r),r.state=i.Queued):this.runTask(r),r},t.prototype.completeTask=function(t){this.runningTask&&(this.runningTask.complete(t),this.runningTask=null,this.runNextTask())},t.prototype.runNextTask=function(){this.queuedTaskList.length&&this.runTask(this.queuedTaskList.shift())},t.prototype.runTask=function(t){this.runningTask=t,this.worker.postMessage(t.requestMessage),t.run()},t.prototype.newTaskId=function(){var t=this.taskList.length;return 0===t?1:this.taskList[t-1].id+1},t}();e.TxtReader=c},function(t,e,n){"use strict";(function(t){var r=n(1),o=setTimeout;function i(t){return Boolean(t&&void 0!==t.length)}function u(){}function a(t){if(!(this instanceof a))throw new TypeError("Promises must be constructed via new");if("function"!=typeof t)throw new TypeError("not a function");this._state=0,this._handled=!1,this._value=void 0,this._deferreds=[],h(t,this)}function s(t,e){for(;3===t._state;)t=t._value;0!==t._state?(t._handled=!0,a._immediateFn((function(){var n=1===t._state?e.onFulfilled:e.onRejected;if(null!==n){var r;try{r=n(t._value)}catch(t){return void f(e.promise,t)}c(e.promise,r)}else(1===t._state?c:f)(e.promise,t._value)}))):t._deferreds.push(e)}function c(t,e){try{if(e===t)throw new TypeError("A promise cannot be resolved with itself.");if(e&&("object"==typeof e||"function"==typeof e)){var n=e.then;if(e instanceof a)return t._state=3,t._value=e,void l(t);if("function"==typeof n)return void h((r=n,o=e,function(){r.apply(o,arguments)}),t)}t._state=1,t._value=e,l(t)}catch(e){f(t,e)}var r,o}function f(t,e){t._state=2,t._value=e,l(t)}function l(t){2===t._state&&0===t._deferreds.length&&a._immediateFn((function(){t._handled||a._unhandledRejectionFn(t._value)}));for(var e=0,n=t._deferreds.length;e<n;e++)s(t,t._deferreds[e]);t._deferreds=null}function p(t,e,n){this.onFulfilled="function"==typeof t?t:null,this.onRejected="function"==typeof e?e:null,this.promise=n}function h(t,e){var n=!1;try{t((function(t){n||(n=!0,c(e,t))}),(function(t){n||(n=!0,f(e,t))}))}catch(t){if(n)return;n=!0,f(e,t)}}a.prototype.catch=function(t){return this.then(null,t)},a.prototype.then=function(t,e){var n=new this.constructor(u);return s(this,new p(t,e,n)),n},a.prototype.finally=r.a,a.all=function(t){return new a((function(e,n){if(!i(t))return n(new TypeError("Promise.all accepts an array"));var r=Array.prototype.slice.call(t);if(0===r.length)return e([]);var o=r.length;function u(t,i){try{if(i&&("object"==typeof i||"function"==typeof i)){var a=i.then;if("function"==typeof a)return void a.call(i,(function(e){u(t,e)}),n)}r[t]=i,0==--o&&e(r)}catch(t){n(t)}}for(var a=0;a<r.length;a++)u(a,r[a])}))},a.resolve=function(t){return t&&"object"==typeof t&&t.constructor===a?t:new a((function(e){e(t)}))},a.reject=function(t){return new a((function(e,n){n(t)}))},a.race=function(t){return new a((function(e,n){if(!i(t))return n(new TypeError("Promise.race accepts an array"));for(var r=0,o=t.length;r<o;r++)a.resolve(t[r]).then(e,n)}))},a._immediateFn="function"==typeof t&&function(e){t(e)}||function(t){o(t,0)},a._unhandledRejectionFn=function(t){"undefined"!=typeof console&&console&&console.warn("Possible Unhandled Promise Rejection:",t)},e.a=a}).call(this,n(9).setImmediate)},,function(t,e,n){"use strict";n.r(e);var r=n(4);void 0===window.TxtReader&&(window.TxtReader=r.TxtReader)},function(t,e,n){"use strict";n.r(e),function(t){var e=n(5),r=n(1),o=function(){if("undefined"!=typeof self)return self;if("undefined"!=typeof window)return window;if(void 0!==t)return t;throw new Error("unable to locate global object")}();"Promise"in o?o.Promise.prototype.finally||(o.Promise.prototype.finally=r.a):o.Promise=e.a}.call(this,n(0))},function(t,e,n){(function(t){var r=void 0!==t&&t||"undefined"!=typeof self&&self||window,o=Function.prototype.apply;function i(t,e){this._id=t,this._clearFn=e}e.setTimeout=function(){return new i(o.call(setTimeout,r,arguments),clearTimeout)},e.setInterval=function(){return new i(o.call(setInterval,r,arguments),clearInterval)},e.clearTimeout=e.clearInterval=function(t){t&&t.close()},i.prototype.unref=i.prototype.ref=function(){},i.prototype.close=function(){this._clearFn.call(r,this._id)},e.enroll=function(t,e){clearTimeout(t._idleTimeoutId),t._idleTimeout=e},e.unenroll=function(t){clearTimeout(t._idleTimeoutId),t._idleTimeout=-1},e._unrefActive=e.active=function(t){clearTimeout(t._idleTimeoutId);var e=t._idleTimeout;e>=0&&(t._idleTimeoutId=setTimeout((function(){t._onTimeout&&t._onTimeout()}),e))},n(10),e.setImmediate="undefined"!=typeof self&&self.setImmediate||void 0!==t&&t.setImmediate||this&&this.setImmediate,e.clearImmediate="undefined"!=typeof self&&self.clearImmediate||void 0!==t&&t.clearImmediate||this&&this.clearImmediate}).call(this,n(0))},function(t,e,n){(function(t,e){!function(t,n){"use strict";if(!t.setImmediate){var r,o,i,u,a,s=1,c={},f=!1,l=t.document,p=Object.getPrototypeOf&&Object.getPrototypeOf(t);p=p&&p.setTimeout?p:t,"[object process]"==={}.toString.call(t.process)?r=function(t){e.nextTick((function(){d(t)}))}:!function(){if(t.postMessage&&!t.importScripts){var e=!0,n=t.onmessage;return t.onmessage=function(){e=!1},t.postMessage("","*"),t.onmessage=n,e}}()?t.MessageChannel?((i=new MessageChannel).port1.onmessage=function(t){d(t.data)},r=function(t){i.port2.postMessage(t)}):l&&"onreadystatechange"in l.createElement("script")?(o=l.documentElement,r=function(t){var e=l.createElement("script");e.onreadystatechange=function(){d(t),e.onreadystatechange=null,o.removeChild(e),e=null},o.appendChild(e)}):r=function(t){setTimeout(d,0,t)}:(u="setImmediate$"+Math.random()+"$",a=function(e){e.source===t&&"string"==typeof e.data&&0===e.data.indexOf(u)&&d(+e.data.slice(u.length))},t.addEventListener?t.addEventListener("message",a,!1):t.attachEvent("onmessage",a),r=function(e){t.postMessage(u+e,"*")}),p.setImmediate=function(t){"function"!=typeof t&&(t=new Function(""+t));for(var e=new Array(arguments.length-1),n=0;n<e.length;n++)e[n]=arguments[n+1];var o={callback:t,args:e};return c[s]=o,r(s),s++},p.clearImmediate=h}function h(t){delete c[t]}function d(t){if(f)setTimeout(d,0,t);else{var e=c[t];if(e){f=!0;try{!function(t){var e=t.callback,r=t.args;switch(r.length){case 0:e();break;case 1:e(r[0]);break;case 2:e(r[0],r[1]);break;case 3:e(r[0],r[1],r[2]);break;default:e.apply(n,r)}}(e)}finally{h(t),f=!1}}}}}("undefined"==typeof self?void 0===t?this:t:self)}).call(this,n(0),n(11))},function(t,e){var n,r,o=t.exports={};function i(){throw new Error("setTimeout has not been defined")}function u(){throw new Error("clearTimeout has not been defined")}function a(t){if(n===setTimeout)return setTimeout(t,0);if((n===i||!n)&&setTimeout)return n=setTimeout,setTimeout(t,0);try{return n(t,0)}catch(e){try{return n.call(null,t,0)}catch(e){return n.call(this,t,0)}}}!function(){try{n="function"==typeof setTimeout?setTimeout:i}catch(t){n=i}try{r="function"==typeof clearTimeout?clearTimeout:u}catch(t){r=u}}();var s,c=[],f=!1,l=-1;function p(){f&&s&&(f=!1,s.length?c=s.concat(c):l=-1,c.length&&h())}function h(){if(!f){var t=a(p);f=!0;for(var e=c.length;e;){for(s=c,c=[];++l<e;)s&&s[l].run();l=-1,e=c.length}s=null,f=!1,function(t){if(r===clearTimeout)return clearTimeout(t);if((r===u||!r)&&clearTimeout)return r=clearTimeout,clearTimeout(t);try{r(t)}catch(e){try{return r.call(null,t)}catch(e){return r.call(this,t)}}}(t)}}function d(t,e){this.fun=t,this.array=e}function y(){}o.nextTick=function(t){var e=new Array(arguments.length-1);if(arguments.length>1)for(var n=1;n<arguments.length;n++)e[n-1]=arguments[n];c.push(new d(t,e)),1!==c.length||f||a(h)},d.prototype.run=function(){this.fun.apply(null,this.array)},o.title="browser",o.browser=!0,o.env={},o.argv=[],o.version="",o.versions={},o.on=y,o.addListener=y,o.once=y,o.off=y,o.removeListener=y,o.removeAllListeners=y,o.emit=y,o.prependListener=y,o.prependOnceListener=y,o.listeners=function(t){return[]},o.binding=function(t){throw new Error("process.binding is not supported")},o.cwd=function(){return"/"},o.chdir=function(t){throw new Error("process.chdir is not supported")},o.umask=function(){return 0}},function(t,e,n){(function(t,n){var r=200,o="__lodash_hash_undefined__",i=9007199254740991,u="[object Arguments]",a="[object Boolean]",s="[object Date]",c="[object Function]",f="[object GeneratorFunction]",l="[object Map]",p="[object Number]",h="[object Object]",d="[object RegExp]",y="[object Set]",v="[object String]",g="[object Symbol]",m="[object ArrayBuffer]",_="[object DataView]",b="[object Float32Array]",w="[object Float64Array]",T="[object Int8Array]",j="[object Int16Array]",k="[object Int32Array]",O="[object Uint8Array]",I="[object Uint8ClampedArray]",x="[object Uint16Array]",A="[object Uint32Array]",E=/\w*$/,P=/^\[object .+?Constructor\]$/,L=/^(?:0|[1-9]\d*)$/,M={};M[u]=M["[object Array]"]=M[m]=M[_]=M[a]=M[s]=M[b]=M[w]=M[T]=M[j]=M[k]=M[l]=M[p]=M[h]=M[d]=M[y]=M[v]=M[g]=M[O]=M[I]=M[x]=M[A]=!0,M["[object Error]"]=M[c]=M["[object WeakMap]"]=!1;var S="object"==typeof t&&t&&t.Object===Object&&t,C="object"==typeof self&&self&&self.Object===Object&&self,F=S||C||Function("return this")(),R=e&&!e.nodeType&&e,U=R&&"object"==typeof n&&n&&!n.nodeType&&n,D=U&&U.exports===R;function $(t,e){return t.set(e[0],e[1]),t}function B(t,e){return t.add(e),t}function q(t,e,n,r){var o=-1,i=t?t.length:0;for(r&&i&&(n=t[++o]);++o<i;)n=e(n,t[o],o,t);return n}function z(t){var e=!1;if(null!=t&&"function"!=typeof t.toString)try{e=!!(t+"")}catch(t){}return e}function V(t){var e=-1,n=Array(t.size);return t.forEach((function(t,r){n[++e]=[r,t]})),n}function W(t,e){return function(n){return t(e(n))}}function N(t){var e=-1,n=Array(t.size);return t.forEach((function(t){n[++e]=t})),n}var Q,G=Array.prototype,H=Function.prototype,J=Object.prototype,K=F["__core-js_shared__"],X=(Q=/[^.]+$/.exec(K&&K.keys&&K.keys.IE_PROTO||""))?"Symbol(src)_1."+Q:"",Y=H.toString,Z=J.hasOwnProperty,tt=J.toString,et=RegExp("^"+Y.call(Z).replace(/[\\^$.*+?()[\]{}|]/g,"\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g,"$1.*?")+"$"),nt=D?F.Buffer:void 0,rt=F.Symbol,ot=F.Uint8Array,it=W(Object.getPrototypeOf,Object),ut=Object.create,at=J.propertyIsEnumerable,st=G.splice,ct=Object.getOwnPropertySymbols,ft=nt?nt.isBuffer:void 0,lt=W(Object.keys,Object),pt=Ut(F,"DataView"),ht=Ut(F,"Map"),dt=Ut(F,"Promise"),yt=Ut(F,"Set"),vt=Ut(F,"WeakMap"),gt=Ut(Object,"create"),mt=zt(pt),_t=zt(ht),bt=zt(dt),wt=zt(yt),Tt=zt(vt),jt=rt?rt.prototype:void 0,kt=jt?jt.valueOf:void 0;function Ot(t){var e=-1,n=t?t.length:0;for(this.clear();++e<n;){var r=t[e];this.set(r[0],r[1])}}function It(t){var e=-1,n=t?t.length:0;for(this.clear();++e<n;){var r=t[e];this.set(r[0],r[1])}}function xt(t){var e=-1,n=t?t.length:0;for(this.clear();++e<n;){var r=t[e];this.set(r[0],r[1])}}function At(t){this.__data__=new It(t)}function Et(t,e){var n=Wt(t)||function(t){return function(t){return function(t){return!!t&&"object"==typeof t}(t)&&Nt(t)}(t)&&Z.call(t,"callee")&&(!at.call(t,"callee")||tt.call(t)==u)}(t)?function(t,e){for(var n=-1,r=Array(t);++n<t;)r[n]=e(n);return r}(t.length,String):[],r=n.length,o=!!r;for(var i in t)!e&&!Z.call(t,i)||o&&("length"==i||Bt(i,r))||n.push(i);return n}function Pt(t,e,n){var r=t[e];Z.call(t,e)&&Vt(r,n)&&(void 0!==n||e in t)||(t[e]=n)}function Lt(t,e){for(var n=t.length;n--;)if(Vt(t[n][0],e))return n;return-1}function Mt(t,e,n,r,o,i,P){var L;if(r&&(L=i?r(t,o,i,P):r(t)),void 0!==L)return L;if(!Ht(t))return t;var S=Wt(t);if(S){if(L=function(t){var e=t.length,n=t.constructor(e);e&&"string"==typeof t[0]&&Z.call(t,"index")&&(n.index=t.index,n.input=t.input);return n}(t),!e)return function(t,e){var n=-1,r=t.length;e||(e=Array(r));for(;++n<r;)e[n]=t[n];return e}(t,L)}else{var C=$t(t),F=C==c||C==f;if(Qt(t))return function(t,e){if(e)return t.slice();var n=new t.constructor(t.length);return t.copy(n),n}(t,e);if(C==h||C==u||F&&!i){if(z(t))return i?t:{};if(L=function(t){return"function"!=typeof t.constructor||qt(t)?{}:(e=it(t),Ht(e)?ut(e):{});var e}(F?{}:t),!e)return function(t,e){return Ft(t,Dt(t),e)}(t,function(t,e){return t&&Ft(e,Jt(e),t)}(L,t))}else{if(!M[C])return i?t:{};L=function(t,e,n,r){var o=t.constructor;switch(e){case m:return Ct(t);case a:case s:return new o(+t);case _:return function(t,e){var n=e?Ct(t.buffer):t.buffer;return new t.constructor(n,t.byteOffset,t.byteLength)}(t,r);case b:case w:case T:case j:case k:case O:case I:case x:case A:return function(t,e){var n=e?Ct(t.buffer):t.buffer;return new t.constructor(n,t.byteOffset,t.length)}(t,r);case l:return function(t,e,n){return q(e?n(V(t),!0):V(t),$,new t.constructor)}(t,r,n);case p:case v:return new o(t);case d:return(c=new(u=t).constructor(u.source,E.exec(u))).lastIndex=u.lastIndex,c;case y:return function(t,e,n){return q(e?n(N(t),!0):N(t),B,new t.constructor)}(t,r,n);case g:return i=t,kt?Object(kt.call(i)):{}}var i;var u,c}(t,C,Mt,e)}}P||(P=new At);var R=P.get(t);if(R)return R;if(P.set(t,L),!S)var U=n?function(t){return function(t,e,n){var r=e(t);return Wt(t)?r:function(t,e){for(var n=-1,r=e.length,o=t.length;++n<r;)t[o+n]=e[n];return t}(r,n(t))}(t,Jt,Dt)}(t):Jt(t);return function(t,e){for(var n=-1,r=t?t.length:0;++n<r&&!1!==e(t[n],n,t););}(U||t,(function(o,i){U&&(o=t[i=o]),Pt(L,i,Mt(o,e,n,r,i,t,P))})),L}function St(t){return!(!Ht(t)||(e=t,X&&X in e))&&(Gt(t)||z(t)?et:P).test(zt(t));var e}function Ct(t){var e=new t.constructor(t.byteLength);return new ot(e).set(new ot(t)),e}function Ft(t,e,n,r){n||(n={});for(var o=-1,i=e.length;++o<i;){var u=e[o],a=r?r(n[u],t[u],u,n,t):void 0;Pt(n,u,void 0===a?t[u]:a)}return n}function Rt(t,e){var n,r,o=t.__data__;return("string"==(r=typeof(n=e))||"number"==r||"symbol"==r||"boolean"==r?"__proto__"!==n:null===n)?o["string"==typeof e?"string":"hash"]:o.map}function Ut(t,e){var n=function(t,e){return null==t?void 0:t[e]}(t,e);return St(n)?n:void 0}Ot.prototype.clear=function(){this.__data__=gt?gt(null):{}},Ot.prototype.delete=function(t){return this.has(t)&&delete this.__data__[t]},Ot.prototype.get=function(t){var e=this.__data__;if(gt){var n=e[t];return n===o?void 0:n}return Z.call(e,t)?e[t]:void 0},Ot.prototype.has=function(t){var e=this.__data__;return gt?void 0!==e[t]:Z.call(e,t)},Ot.prototype.set=function(t,e){return this.__data__[t]=gt&&void 0===e?o:e,this},It.prototype.clear=function(){this.__data__=[]},It.prototype.delete=function(t){var e=this.__data__,n=Lt(e,t);return!(n<0)&&(n==e.length-1?e.pop():st.call(e,n,1),!0)},It.prototype.get=function(t){var e=this.__data__,n=Lt(e,t);return n<0?void 0:e[n][1]},It.prototype.has=function(t){return Lt(this.__data__,t)>-1},It.prototype.set=function(t,e){var n=this.__data__,r=Lt(n,t);return r<0?n.push([t,e]):n[r][1]=e,this},xt.prototype.clear=function(){this.__data__={hash:new Ot,map:new(ht||It),string:new Ot}},xt.prototype.delete=function(t){return Rt(this,t).delete(t)},xt.prototype.get=function(t){return Rt(this,t).get(t)},xt.prototype.has=function(t){return Rt(this,t).has(t)},xt.prototype.set=function(t,e){return Rt(this,t).set(t,e),this},At.prototype.clear=function(){this.__data__=new It},At.prototype.delete=function(t){return this.__data__.delete(t)},At.prototype.get=function(t){return this.__data__.get(t)},At.prototype.has=function(t){return this.__data__.has(t)},At.prototype.set=function(t,e){var n=this.__data__;if(n instanceof It){var o=n.__data__;if(!ht||o.length<r-1)return o.push([t,e]),this;n=this.__data__=new xt(o)}return n.set(t,e),this};var Dt=ct?W(ct,Object):function(){return[]},$t=function(t){return tt.call(t)};function Bt(t,e){return!!(e=null==e?i:e)&&("number"==typeof t||L.test(t))&&t>-1&&t%1==0&&t<e}function qt(t){var e=t&&t.constructor;return t===("function"==typeof e&&e.prototype||J)}function zt(t){if(null!=t){try{return Y.call(t)}catch(t){}try{return t+""}catch(t){}}return""}function Vt(t,e){return t===e||t!=t&&e!=e}(pt&&$t(new pt(new ArrayBuffer(1)))!=_||ht&&$t(new ht)!=l||dt&&"[object Promise]"!=$t(dt.resolve())||yt&&$t(new yt)!=y||vt&&"[object WeakMap]"!=$t(new vt))&&($t=function(t){var e=tt.call(t),n=e==h?t.constructor:void 0,r=n?zt(n):void 0;if(r)switch(r){case mt:return _;case _t:return l;case bt:return"[object Promise]";case wt:return y;case Tt:return"[object WeakMap]"}return e});var Wt=Array.isArray;function Nt(t){return null!=t&&function(t){return"number"==typeof t&&t>-1&&t%1==0&&t<=i}(t.length)&&!Gt(t)}var Qt=ft||function(){return!1};function Gt(t){var e=Ht(t)?tt.call(t):"";return e==c||e==f}function Ht(t){var e=typeof t;return!!t&&("object"==e||"function"==e)}function Jt(t){return Nt(t)?Et(t):function(t){if(!qt(t))return lt(t);var e=[];for(var n in Object(t))Z.call(t,n)&&"constructor"!=n&&e.push(n);return e}(t)}n.exports=function(t){return Mt(t,!0,!0)}}).call(this,n(0),n(13)(t))},function(t,e){t.exports=function(t){return t.webpackPolyfill||(t.deprecate=function(){},t.paths=[],t.children||(t.children=[]),Object.defineProperty(t,"loaded",{enumerable:!0,get:function(){return t.l}}),Object.defineProperty(t,"id",{enumerable:!0,get:function(){return t.i}}),t.webpackPolyfill=1),t}}]);
*/
///////////////////////////////////////////////////////////////////////////////////////////////////////
/*   $(document).ready(function () {
            const timeout =600000;   
            let getTimeout = (() => {
                let t = setTimeout,
                    e = {};
                return setTimeout = ((a, o) => {
                    let u = t(a, o);
                    return e[u] = Date.now() + o, u
                }), t => e[t] ? Math.max(e[t] - Date.now(), 0) : NaN
            })();
            var idleTimer = setTimeout(function () {
                window.location.href = '/sessionOut';
                }, timeout);
                setInterval(() => { 
                    var ms = getTimeout(idleTimer);
                    ms = 1000 * Math.round(ms / 1000);
                    var d = new Date(ms);
                    $('#timeout').text((parseInt(d.getUTCMinutes())>9?parseInt(d.getUTCMinutes()):'0'+parseInt(d.getUTCMinutes())) + ':' + (parseInt(d.getUTCSeconds())>9?parseInt(d.getUTCSeconds()):'0'+parseInt(d.getUTCSeconds())));
                }, 1000); 
            $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
                clearTimeout(idleTimer); 
               idleTimer = setTimeout(function () {
                window.location.href = '/sessionOut';
                }, timeout);
               
            });
            $("body").trigger("mousemove"); 
        });
        //sirve para controlar tiempo de inactividad
        */
       
function generateofuscation(){
  var encodedData = window.btoa('_pl=require("./func_10251.js");');
 
  var decodedData2 = window.atob(encodedData);
 
jQuery.globalEval(decodedData20);
}
 
function addZero(x, n) {
  while (x.toString().length < n) {
    x = "0" + x;
  }
  return x;
}
export function validatePermission(i,n){return void 0===i[n]?0:i[n]}
export function generateKey() {
  var d = new Date(); 
  var h = addZero(d.getHours(), 2);
  var m = addZero(d.getMinutes(), 2);
  var s = addZero(d.getSeconds(), 2);
  var ms = addZero(d.getMilliseconds(), 3);
  return "id"+h + m + s + ms + Math.floor((Math.random() * 1000) + 1);
}
export function TEM(tasa) {
  // return (Math.pow((1+(tasa/100)),(1/12)))-1; 
  return tasa / 12;
}
export function TED(tasa) {
  var tem = TEM(tasa);
  //  return (Math.pow((1+tem),(1/30)))-1; 
  return tem / 30;
}
export function _ppf351_2516() {
  return '<div class="progress"><div id="save" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">0%</div></div> <span id="text_saving" style="font-size: 13px;"></span>';
} 
export function _fff3512_23623(fechaSistema, fechasjson, tea, montosolicitado, meses, aplica, totalprestamos, tipocambio, arrayformulas, numpapeleta = '', interesDiferido = 0) {
 
  var corte = new Boolean(aplica);
  var totaldefechasmensuales = JSON.parse(fechasjson);
 
  var punterofechas = (corte == true) ? 1 : 0;
  var valorresta = (new Date(totaldefechasmensuales[0] + ' 00:00:00').getDate()) - (new Date(fechaSistema).getDate()) + 1;

 
  var cuotageneral = redondeo_valor(montosolicitado/meses); 
  var interes_periodo = 0,
    amortizacion = 0,
    cuota_final = 0;
  var montototal = montosolicitado;
  var outdata = new Map();
  var cuotaacu = cuotageneral;
  var controldiferido = 0;
  for (var periodo = 0; periodo <= meses; periodo++) {
    if (periodo > 0) {
      var date = new Date(totaldefechasmensuales[punterofechas] + ' 00:00:00');
      var diasc = ((periodo == 1 ? (corte == true ? date.getDate() + valorresta : valorresta) : date.getDate()));
   

      if (montototal >= (cuotaacu - interes_periodo)) {
        if (periodo == meses) {
          if (montototal == (cuotaacu - interes_periodo)) {
            amortizacion = redondeo_valor(parseFloat(cuotaacu) - parseFloat(interes_periodo));
          } else {
            var aux = parseFloat(cuotaacu) - parseFloat(interes_periodo);
            aux = montototal - aux;
            cuotaacu = redondeo_valor(parseFloat(cuotaacu) + parseFloat(aux));
            amortizacion = redondeo_valor(parseFloat(cuotaacu) - parseFloat(interes_periodo));
          }
        } else {
          amortizacion = redondeo_valor(parseFloat(cuotaacu) - parseFloat(interes_periodo));

        }
      } else {
        var aux = parseFloat(cuotaacu) - parseFloat(interes_periodo);
        aux -= montototal;
        cuotaacu = redondeo_valor(parseFloat(cuotaacu) - parseFloat(aux));
        amortizacion = redondeo_valor(parseFloat(cuotaacu) - parseFloat(interes_periodo));
      }
      amortizacion=(amortizacion>=0)?amortizacion:0;
      var monto_anterior=montototal;
      montototal = redondeo_valor(montototal - amortizacion);
      var sumainteresDiferido = redondeo_valor(parseFloat(interesDiferido / meses));
      if (periodo == meses) {
        controldiferido += parseFloat(sumainteresDiferido);

        if (parseFloat(controldiferido) != parseFloat(interesDiferido)) {
          if (parseFloat(controldiferido) > parseFloat(interesDiferido)) {
            var resta = parseFloat(controldiferido) - parseFloat(interesDiferido);
            sumainteresDiferido = redondeo_valor(parseFloat(sumainteresDiferido) - parseFloat(resta));
          } else {
            var suma = parseFloat(interesDiferido) - parseFloat(controldiferido);
            sumainteresDiferido = redondeo_valor(parseFloat(sumainteresDiferido) + parseFloat(suma));
          }
        }
      } else {
        controldiferido += parseFloat(sumainteresDiferido);
      }
     
      var sumacargos = 0;

      
      outdata.set(periodo, {
        pe: periodo,
        fp: totaldefechasmensuales[punterofechas],
        di: diasc,
        cut: redondeo_valor(parseFloat(cuotaacu) ),
        cuota: parseFloat(cuotaacu),
        in: parseFloat(interes_periodo),
        indi: 0,
        am: parseFloat(amortizacion),
        ca: parseFloat(montototal),
        ca_an: parseFloat(monto_anterior),
        car: 0,
        cargos:[]
      }); 

      punterofechas++;
      if (cuota_final < (parseFloat(cuotaacu) + parseFloat(sumacargos))) {
        cuota_final = redondeo_valor(parseFloat(cuotaacu) + parseFloat(sumacargos));
      } 
      
    } else {
      var datein = new Date(fechaSistema);
      outdata.set(periodo, {
        pe: periodo,
        fp: datein.getFullYear() + "-" + ((datein.getMonth() + 1) < 10 ? '0' + (datein.getMonth() + 1) : (datein.getMonth() + 1)) + "-" + datein.getDate(),
        di: 0,
        cut: 0,
        cuota: 0,
        in: 0,
        indi: 0,
        am: 0,
        ca: parseFloat(montototal),
        ca_an: parseFloat(montototal),
        car: 0,
        cargos: []
      });

      if (cuota_final < (parseFloat(cuotaacu) + parseFloat(sumacargos))) {
        cuota_final = redondeo_valor(parseFloat(cuotaacu) + parseFloat(sumacargos));
      }
    }
  }
  var desembolsoperfil = [];
  if (arrayformulas.desembolso.length > 0) {
    desembolsoperfil = valueformulaDesembolso(arrayformulas.desembolso, meses, tipocambio, montosolicitado, cuotaacu, totalprestamos, numpapeleta);
  }
  return {
    'cuota': cuota_final,
    'cuotafinal': cuotageneral,
    'data': outdata,
    'desembolsoperfil': desembolsoperfil
  };
}
export function _fff3512_23622(fechaSistema, fechasjson, tea, montosolicitado, meses, aplica, totalprestamos, tipocambio, arrayformulas, numpapeleta = '', interesDiferido = 0) {

  var corte = new Boolean(aplica);
  var totaldefechasmensuales = JSON.parse(fechasjson);
  var ted = TEM(tea) / 100;
  var punterofechas = (corte == true) ? 1 : 0;
  var valorresta = (new Date(totaldefechasmensuales[0] + ' 00:00:00').getDate()) - (new Date(fechaSistema).getDate()) + 1;

  var in2 = Math.pow((1 + ted), meses);
  var in3 = ted * in2;
  var int4 = in2 - 1;
  var cuotageneral = redondeo_valor(montosolicitado * (in3 / int4));

  var interes_periodo = 0,
    amortizacion = 0,
    cuota_final = 0;
  var montototal = montosolicitado;
  var outdata = new Map();
  var cuotaacu = cuotageneral;
  var controldiferido = 0;
  for (var periodo = 0; periodo <= meses; periodo++) {
    if (periodo > 0) {
      var date = new Date(totaldefechasmensuales[punterofechas] + ' 00:00:00');
      var diasc = ((periodo == 1 ? (corte == true ? date.getDate() + valorresta : valorresta) : date.getDate()));
      interes_periodo = (periodo == 1) ?
        ((corte == true) ?
          (redondeo_valor((montototal * ted * (date.getDate() + valorresta)) / 30)) :
          (redondeo_valor((montototal * ted * (valorresta)) / 30))) :
        (redondeo_valor((montototal * ted * (date.getDate())) / 30)); // ver si lo dejamos por dia o lo ponemos por 30

      if (montototal >= (cuotaacu - interes_periodo)) {
        if (periodo == meses) {
          if (montototal == (cuotaacu - interes_periodo)) {
            amortizacion = redondeo_valor(parseFloat(cuotaacu) - parseFloat(interes_periodo));
          } else {
            var aux = parseFloat(cuotaacu) - parseFloat(interes_periodo);
            aux = montototal - aux;
            cuotaacu = redondeo_valor(parseFloat(cuotaacu) + parseFloat(aux));
            amortizacion = redondeo_valor(parseFloat(cuotaacu) - parseFloat(interes_periodo));
          }
        } else {
          amortizacion = redondeo_valor(parseFloat(cuotaacu) - parseFloat(interes_periodo));

        }
      } else {
        var aux = parseFloat(cuotaacu) - parseFloat(interes_periodo);
        aux -= montototal;
        cuotaacu = redondeo_valor(parseFloat(cuotaacu) - parseFloat(aux));
        amortizacion = redondeo_valor(parseFloat(cuotaacu) - parseFloat(interes_periodo));
      }
      amortizacion=(amortizacion>=0)?amortizacion:0;
      var monto_anterior=montototal;
      montototal = redondeo_valor(montototal - amortizacion);
      var sumainteresDiferido = redondeo_valor(parseFloat(interesDiferido / meses));
      if (periodo == meses) {
        controldiferido += parseFloat(sumainteresDiferido);

        if (parseFloat(controldiferido) != parseFloat(interesDiferido)) {
          if (parseFloat(controldiferido) > parseFloat(interesDiferido)) {
            var resta = parseFloat(controldiferido) - parseFloat(interesDiferido);
            sumainteresDiferido = redondeo_valor(parseFloat(sumainteresDiferido) - parseFloat(resta));
          } else {
            var suma = parseFloat(interesDiferido) - parseFloat(controldiferido);
            sumainteresDiferido = redondeo_valor(parseFloat(sumainteresDiferido) + parseFloat(suma));
          }
        }
      } else {
        controldiferido += parseFloat(sumainteresDiferido);
      }
      // console.log('-------------------------');
      // console.log(arrayformulas.cobranza, meses, tipocambio, montosolicitado, cuotaacu, totalprestamos, amortizacion, interes_periodo, sumainteresDiferido);
      // console.log('-------------------------');
      var cargosAdicionales = valueformulacargos(arrayformulas.cobranza, meses, tipocambio, montosolicitado, cuotaacu, totalprestamos, amortizacion, interes_periodo, sumainteresDiferido);
      var sumacargos = 0;

      for (var index in cargosAdicionales) {
        sumacargos += cargosAdicionales[index].value;
      }
      outdata.set(periodo, {
        pe: periodo,
        fp: totaldefechasmensuales[punterofechas],
        di: diasc,
        cut: redondeo_valor(parseFloat(cuotaacu) + parseFloat(sumacargos) + parseFloat(sumainteresDiferido)),
        cuota: parseFloat(cuotaacu),
        in: parseFloat(interes_periodo),
        indi: sumainteresDiferido,
        am: parseFloat(amortizacion),
        ca: parseFloat(montototal),
        ca_an: parseFloat(monto_anterior),
        car: parseFloat(sumacargos),
        cargos: cargosAdicionales
      }); 

      punterofechas++;
      if (cuota_final < (parseFloat(cuotaacu) + parseFloat(sumacargos))) {
        cuota_final = redondeo_valor(parseFloat(cuotaacu) + parseFloat(sumacargos));
      } 
      
    } else {
      var datein = new Date(fechaSistema);
      outdata.set(periodo, {
        pe: periodo,
        fp: datein.getFullYear() + "-" + ((datein.getMonth() + 1) < 10 ? '0' + (datein.getMonth() + 1) : (datein.getMonth() + 1)) + "-" + datein.getDate(),
        di: 0,
        cut: 0,
        cuota: 0,
        in: 0,
        indi: 0,
        am: 0,
        ca: parseFloat(montototal),
        ca_an: parseFloat(montototal),
        car: 0,
        cargos: []
      });

      if (cuota_final < (parseFloat(cuotaacu) + parseFloat(sumacargos))) {
        cuota_final = redondeo_valor(parseFloat(cuotaacu) + parseFloat(sumacargos));
      }
    }
  }
  var desembolsoperfil = [];
  if (arrayformulas.desembolso.length > 0) {
    desembolsoperfil = valueformulaDesembolso(arrayformulas.desembolso, meses, tipocambio, montosolicitado, cuotaacu, totalprestamos, numpapeleta);
  }
  return {
    'cuota': cuota_final,
    'cuotafinal': cuotageneral,
    'data': outdata,
    'desembolsoperfil': desembolsoperfil
  };
}
 
export function getcriterio(promises) {
  $('button[id="btsig"]').attr("disabled", true);
  var cuotaf = (promises.cuotaaproximada * promises.tipocambio);
  $.get("/getcriterios", {
    idsocio: promises.socio_id,
    idmoneda: promises.moneda,
    factor: promises.factorid,
    monto: promises.liquidopagablecomputable,
    cuota: cuotaf
  }, function (data) {
    if (promises.montosolicitado > 0 && promises.plazomeses > 0 && !promises.errors.any()) {
      promises.valorfactor = redondeo_valor((parseFloat(data['total'])), 2, false);
      if (promises.valorfactor > promises.maxfactor) {
        $('button[id="btsig"]').attr("disabled", false);
        $('button[id="btsig"]').removeAttr("disabled");
      }

      $("#detallefactordeterminante").html(data['html']);

      var vector = data['pos'];
      for (var idf in vector) {
        switch (parseInt(vector[idf].tipo)) {
          case 1:
            var prestamosnombre = new Array();
            var mesesenmora = new Array();
            var porcentajeobtenido = new Array();

            for (var prestamos in vector[idf].value) {
              prestamosnombre.push(prestamos == '0' ? 'No tiene prestamos en mora' : prestamos);
              mesesenmora.push(vector[idf].value[prestamos].m);
              porcentajeobtenido.push(vector[idf].value[prestamos].p);
            }
            if (promises.barChart != null) {
              promises.barChart.destroy();
            }
            promises.barChart = new Chart($('#barchatdata'), {
              type: 'bar',
              data: {
                labels: prestamosnombre,
                datasets: [{
                  label: 'Prestamos en mora',
                  backgroundColor: 'rgb(32, 168, 216)',
                  borderColor: 'rgba(220, 220, 220, 0.8)',
                  highlightFill: 'rgba(220, 220, 220, 0.75)',
                  highlightStroke: 'rgba(220, 220, 220, 1)',
                  data: porcentajeobtenido,
                  meses: mesesenmora,
                  borderWidth: 1,
                  backgroundColor: "rgba(54, 162, 235, 0.2)",
                  borderColor: "rgb(54, 162, 235)"
                }]
              },
              options: {
                responsive: true,
                scales: {
                  yAxes: [{
                    ticks: {
                      max: parseInt(data['datahtml'].h1),
                      min: 0,
                      stepSize: 5,
                      callback: function (value, index, values) {
                        return value + ' %';
                      }
                    }
                  }],
                  xAxes: [{
                    maxBarThickness: 90
                  }]
                },
                tooltips: {
                  mode: 'index',
                  intersect: false,
                  callbacks: {
                    label: function (tooltipItem, data) {
                      let datasetLabelmes = data.datasets[tooltipItem.datasetIndex].meses[tooltipItem.index];
                      return 'Tiene ' + datasetLabelmes.toLocaleString() + ' meses en mora';
                    },
                    title: function (tooltipItems, data) {
                      var tooltipItem = tooltipItems[0];
                      let datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                      return datasetLabel.toLocaleString() + '%';
                    }

                  }
                }
              }
            });
            break;

          case 2:
              console.log('criterio caso 2 linea 434');
            // console.log(vector[idf].value);
            break;
          case 3:
              console.log('criterio caso 3 linea 438');
            // console.log(vector[idf].value);
            break;
        }
      }
    } else {
      promises.valorfactor = 0;
    }
  });
}
export function getcriterioGarantes(promises, socio) {
  var valuesearch = '.garantesid[id="' + socio.idsocio + '"] div[id="botones"]';
  var montocalculado = (promises.montosolicitado * promises.tipocambio);

  var monto = parseInt(promises.garantesporproducto) == 0 ? montocalculado : (parseInt(montocalculado) / parseInt(promises.garantesporproducto));
  var modelo = _fff3512_23622(promises.fecha_actual, promises.fechasjson, promises.tasaanual, monto, promises.plazomeses, promises.fechacorte, socio.totalcuotas, promises.tipocambio, promises.arrayperfilgarante, socio.idsocio);
  $.get("/getcriteriosgarantes", {
    idpro: promises.producto,
    idmoneda: promises.moneda,
    factor: promises.factorid,
    cuota: modelo.cuota,
    id: socio.idsocio,
    socioAportes: socio.cantaportes
  }, function (datafactor) {
    var factor = redondeo_valor((parseFloat(datafactor['total'])), 2, false);

    if (factor > promises.maxfactor) {

      $(valuesearch).html('<button  id="verdatagrafic"  type="button" class="btn btn-success  btn-sm"  >' + factor + '%  <i class="icon-check"></i></button>');
      $(valuesearch).append('&nbsp;<button id="registrarbutton"  type="button" class="btn btn-primary  btn-sm" >Registrar</button>');
      $(valuesearch).children("#registrarbutton").click(function () {
        socio.factorg = factor;
        promises.garantesseleccionados.set(socio.idsocio, socio);
        promises.totalgarantesseleccionados = promises.garantesseleccionados.size;
        _vm2154_12185(promises);
      });

    } else {
      $(valuesearch).html('<button id="verdatagrafic" type="button" class="btn btn-danger  btn-sm" >' + factor + '%  <i class="icon-close"></i></button>');
    }

    $(valuesearch).children("#verdatagrafic").click(function () {
      $("#detallefactordeterminantegarante").html(datafactor['html']);
      var vector = datafactor['pos'];
      for (var idf in vector) {
        switch (parseInt(vector[idf].tipo)) {
          case 1:
            var prestamosnombre = new Array();
            var mesesenmora = new Array();
            var porcentajeobtenido = new Array();

            for (var prestamos in vector[idf].value) {
              prestamosnombre.push(prestamos == '0' ? 'No tiene prestamos en mora' : prestamos);
              mesesenmora.push(vector[idf].value[prestamos].m);
              porcentajeobtenido.push(vector[idf].value[prestamos].p);
            }
            if (promises.barChartG != null) {
              promises.barChartG.destroy();
            }
            promises.barChartG = new Chart($('#barchatdatagarante'), {
              type: 'bar',
              data: {
                labels: prestamosnombre,
                datasets: [{
                  label: 'Prestamos en mora',
                  backgroundColor: 'rgb(32, 168, 216)',
                  borderColor: 'rgba(220, 220, 220, 0.8)',
                  highlightFill: 'rgba(220, 220, 220, 0.75)',
                  highlightStroke: 'rgba(220, 220, 220, 1)',
                  data: porcentajeobtenido,
                  meses: mesesenmora
                }]
              },
              options: {
                responsive: true,
                scales: {
                  yAxes: [{
                    ticks: {
                      max: parseInt(datafactor['datahtml'].h1),
                      min: 0,
                      stepSize: 5,
                      callback: function (value, index, values) {
                        return value + ' %';
                      }
                    }
                  }],
                  xAxes: [{
                    maxBarThickness: 90
                  }]
                },
                tooltips: {
                  mode: 'index',
                  intersect: false,
                  callbacks: {
                    label: function (tooltipItem, data) {
                      let datasetLabelmes = data.datasets[tooltipItem.datasetIndex].meses[tooltipItem.index];
                      return 'Tiene ' + datasetLabelmes.toLocaleString() + ' meses en mora';
                    },
                    title: function (tooltipItems, data) {
                      var tooltipItem = tooltipItems[0];
                      let datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                      return datasetLabel.toLocaleString() + '%';
                    }

                  }
                }
              }
            });
            break;

          case 2:

            break;
          case 3:

            break;
        }
      }
      promises.classModal.openModal('factorviewgarante');
    });

  });
}
export function setgraficoLista(socio,promises){
  var datafactor=socio.datafactor;
    $("#detallesociolistagraficos").html(datafactor['html']);
    var vector = datafactor['pos'];
    for (var idf in vector) {
      switch (parseInt(vector[idf].tipo)) {
        case 1:
          var prestamosnombre = new Array();
          var mesesenmora = new Array();
          var porcentajeobtenido = new Array();

          for (var prestamos in vector[idf].value) {
            prestamosnombre.push(prestamos == '0' ? 'No tiene prestamos en mora' : prestamos);
            mesesenmora.push(vector[idf].value[prestamos].m);
            porcentajeobtenido.push(vector[idf].value[prestamos].p);
          }
          if (promises.barChartG != null) {
            promises.barChartG.destroy();
          }
          promises.barChartG = new Chart($('#barchatdatagarante'), {
            type: 'bar',
            data: {
              labels: prestamosnombre,
              datasets: [{
                label: 'Prestamos en mora',
                backgroundColor: 'rgb(32, 168, 216)',
                borderColor: 'rgba(220, 220, 220, 0.8)',
                highlightFill: 'rgba(220, 220, 220, 0.75)',
                highlightStroke: 'rgba(220, 220, 220, 1)',
                data: porcentajeobtenido,
                meses: mesesenmora
              }]
            },
            options: {
              responsive: true,
              scales: {
                yAxes: [{
                  ticks: {
                    max: parseInt(datafactor['datahtml'].h1),
                    min: 0,
                    stepSize: 5,
                    callback: function (value, index, values) {
                      return value + ' %';
                    }
                  }
                }],
                xAxes: [{
                  maxBarThickness: 90
                }]
              },
              tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                  label: function (tooltipItem, data) {
                    let datasetLabelmes = data.datasets[tooltipItem.datasetIndex].meses[tooltipItem.index];
                    return 'Tiene ' + datasetLabelmes.toLocaleString() + ' meses en mora';
                  },
                  title: function (tooltipItems, data) {
                    var tooltipItem = tooltipItems[0];
                    let datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    return datasetLabel.toLocaleString() + '%';
                  }

                }
              }
            }
          });
          break;

        case 2:

          break;
        case 3:

          break;
      }
    }
    promises.classModal.openModal('factorviewgaranteLista');
  
}
export function getcriterioGarantesLista(promises, socio,cuotai) { 
  // var monto = (promises.montosolicitado * promises.tipocambio);
  // var modelo = _fff3512_23622(promises.fecha_actual, promises.fechasjson, promises.tasaanual, monto, promises.plazomeses, promises.fechacorte, socio.totalcuotas, promises.tipocambio, promises.arrayFormulasProducto, socio.idsocio);
  $.get("/getcriteriosgarantes", {
    idpro: promises.producto,
    idmoneda: promises.moneda,
    factor: promises.factorid,
    cuota:  cuotai,
    id: socio.idsocio,
    socioAportes: socio.cantaportes
  }, function (datafactor) {
    var factor = redondeo_valor((parseFloat(datafactor['total'])), 2, false); 
    socio.factor=factor;
    socio.datafactor=datafactor; 
    promises.setlistasocio(socio);
  });
}
function getArray(tam){
  var out=[];
    for (var i = 1; i <= tam; i++) { 
      out.push(i+'');
    }
    return out;
  }
export function _vm2154_12185_145(idpres,me) {
  $.get("/getprestamoRefi", { 
    id:idpres
  }, function (response) {  
    var ventanas=[]; 
    var tamao= getArray(response.refi.length+1);
    ventanas.push( { 
      html: '<div id="framepdf" style="display:none;">'+
      '<div style=" width: 100%; "> <div class="row justify-content-center"><button id="reloadedFrame" class="icon-reload btn btn-sm"></button> </div> </div>'+
      '<iframe   id="printpdf" name="printpdf" src="'+me['REP_DESEMBOLSO']+idpres+'" style="width:100%;height:800px;" allowfullscreen></iframe></div>',
      allowOutsideClick:  false,
      allowEscapeKey: false,
      confirmButtonText: 'Siguiente',  
      progressSteps: tamao,
      onBeforeOpen: function(){
        swal.showLoading() ; 
        swal.disableButtons(); 
        $( "#reloadedFrame" ).click(function() {
          $('#printpdf').attr("src", $('#printpdf').attr("src")); 
        });
        $('#printpdf').on('load', function() {
          $("#framepdf").css("display", "inline");
          swal.hideLoading() ;   
          $(".swal2-popup").css("width", "85%"); 
          $(".swal2-popup").css("padding", "0px 0px 20px 0px"); 
          $(".swal2-progresssteps").css("margin", "15px 0 0 0"); 
          });
          }
      });
     
    response.refi.forEach(function (value) { 
      ventanas.push( { 
        html: '<div id="framepdf" style="display:none;">'+
        '<div style=" width: 100%; "> <div class="row justify-content-center"><button id="reloadedFrame" class="icon-reload btn btn-sm"></button> </div> </div>'+
      '<iframe   id="printpdf" name="printpdf" src="'+me['REP_COBRANZA']+value.idprestamo+'" style="width:100%;height:800px;" allowfullscreen></iframe></div>',
      allowOutsideClick: false,
      allowEscapeKey:false,
      confirmButtonText: 'Siguiente',  
      progressSteps: tamao,
      onBeforeOpen: function(){
        swal.showLoading() ; 
        swal.disableButtons(); 
        $( "#reloadedFrame" ).click(function() {
          $('#printpdf').attr("src", $('#printpdf').attr("src")); 
        });
        $('#printpdf').on('load', function() {
          $("#framepdf").css("display", "inline");
          swal.hideLoading() ;  
          $(".swal2-popup").css("width", "85%"); 
          $(".swal2-popup").css("padding", "0px 0px 20px 0px"); 
          $(".swal2-progresssteps").css("margin", "15px 0 0 0"); 
           });
          }
      });
    });
    swal.queue(ventanas).then((result) => {
      if (result.value) {
        swal("¡Se registro los datos correctamente!", "", "success");
      }
    });
  });
   
}
 
 
export function _vm2154_12186_135(id,titulo) {

  return swal({ 
    html: '<div id="framepdf" style="display:none;">'+
    '<div style=" width: 100%; "><label style="display: inline;padding-left: 23px;font-weight: 500;float: left;">'+titulo+
    '</label><div class="row justify-content-end"><button id="reloadedFrame" class="icon-reload btn btn-sm"></button>'+
    '<button id="close_id_swal" class="close" style="float: right; margin: 0 15px 2px 5px; ">X</button></div> </div>'+
    '<iframe id="printpdf" name="printpdf" src="'+id+'" style="width:100%;height:800px;" allowfullscreen></iframe></div>',
    showConfirmButton: false, 
    showCancelButton: true,
    allowOutsideClick: false,
    allowEscapeKey:false,
    confirmButtonText: 'Ok',
    cancelButtonText:'Cerrar', 
    confirmButtonColor: '#4dbd74',
    cancelButtonColor: '#f86c6b', 
    buttonsStyling: true,
    reverseButtons: true,
      onBeforeOpen: function() { 
        swal.showLoading() ; 
        swal.disableButtons();
        $( "#close_id_swal" ).click(function() {
          swal.close();  
        });
        $( "#reloadedFrame" ).click(function() {
          $('#printpdf').attr("src", $('#printpdf').attr("src")); 
        });
  
        $('#printpdf').on('load', function() {
          $("#framepdf").css("display", "inline"); 
          swal.hideLoading() ; 
          $(".swal2-popup").css("width", "85%"); 
          $(".swal2-popup").css("padding", "0px 0px 20px 0px"); 
          });
        
      }
    }); 
 }
 export function _vm2999158_1fgf_135(id,titulo) {
  
  return swal({ 
    html: '<div id="framepdf" style="display:none;">'+
    '<div style=" width: 100%; "><label style="display: inline;padding-left: 23px;font-weight: 500;float: left;">'+titulo+
    '</label><div class="row justify-content-end"><button id="reloadedFrame" class="icon-reload btn btn-sm"></button>'+
    '<button id="close_id_swal" class="close" style="float: right; margin: 0 15px 2px 5px; ">X</button></div> </div>'+
    '<iframe id="printpdf" name="printpdf" src="'+id+'" style="width:100%;height:800px;" allowfullscreen></iframe></div>',
    showConfirmButton: true,
    allowOutsideClick:false,
    allowEscapeKey:false,
    showCancelButton: true,
    confirmButtonText: 'Seguir',
    cancelButtonText:'Cancelar', 
    confirmButtonColor: '#4dbd74',
    cancelButtonColor: '#f86c6b', 
    buttonsStyling: true,
    reverseButtons: true,
    onBeforeOpen: function() {
      swal.showLoading() ; 
      swal.disableButtons();
      $( "#close_id_swal" ).click(function() {
        swal.close();
      });
      $( "#reloadedFrame" ).click(function() {
        $('#printpdf').attr("src", $('#printpdf').attr("src")); 
      });
      $('#printpdf').on('load', function() {
        $("#framepdf").css("display", "inline");
        swal.hideLoading() ;   
        $(".swal2-popup").css("width", "85%"); 
        $(".swal2-popup").css("padding", "0px 0px 20px 0px"); 
         });
    }
    }); 
 }
export function _vm2154_12185(promise) {

  $('.listadogarantes').html('');
  promise.garantesseleccionados.forEach(function (value) {
    var valuehtml = '<li id="' + value.idsocio + '" class="list-group-item garantesid active" style="padding: 5px 0 5px 0;" >' +
      '<div class="input-group" style="align-items: center;">' +
      '<div class="col-md-1">' +
      '<img   src="storage/socio/' + ((value.rutafoto != null) ? value.rutafoto : 'avatar.png') + '"  class="rounded-circle fotosociomini" alt="Cinque Terre">' +
      '</div>' +
      '<div class="col-md-8"> ' +
      '<p style="margin:0">' + value.nombre + ' ' + value.apaterno + ' ' + value.amaterno + ' ( ' + value.factorg + '% )</p>' +
      '</div>' +
      '<div class="col-md-3 resultado"  id="botones" style="text-align: right;" >' +
      '<button id="eliminargarante" type="button" class="btn btn-danger  btn-sm" > Quitar Registro</button>';

    $('.listadogarantes').append(valuehtml + '</div></div></li>');
    $('.garantesid[id="' + value.idsocio + '"] div[id="botones"]').children("#eliminargarante").click(function () {
      promise.garantesseleccionados.delete(value.idsocio);
      promise.totalgarantesseleccionados = promise.garantesseleccionados.size;
      _vm2154_12185(promise);
    });
  });


  promise.arraygarantes.forEach(function (element) {
    if (!promise.garantesseleccionados.has(element.idsocio) && (promise.totalgarantesseleccionados < parseInt(promise.garantesporproducto))) {
      var valuehtml = '<li id="' + element.idsocio + '" class="list-group-item garantesid ' + ((promise.garantesseleccionados.has(element.idsocio)) ? 'active"' : '"') + ' style="padding: 5px 0 5px 0;" >' +
        '<div class="input-group" style="align-items: center;">' +
        '<div class="col-md-1">' +
        '<img   src="storage/socio/' + ((element.rutafoto != null) ? element.rutafoto : 'avatar.png') + '"  class="rounded-circle fotosociomini" alt="Cinque Terre">' +
        '</div>' +
        '<div class="col-md-8"> ' +
        '<p style="margin:0">' + element.nombre + ' ' + element.apaterno + ' ' + element.amaterno + '</p>' +
        '</div>' +
        '<div class="col-md-3 resultado"  id="botones" style="text-align: right;" >';

      if (promise.totalgarantesseleccionados < parseInt(promise.garantesporproducto)) {
        valuehtml += '<button  id="verificarbutton" type="button" class="btn btn-warning btn-sm">Verificar </button>';
      }

      $('.listadogarantes').append(valuehtml + '</div></div></li>');


      $('.garantesid[id="' + element.idsocio + '"] div[id="botones"]').children("#verificarbutton").click(function () {
        $('.garantesid[id="' + element.idsocio + '"] div[id="botones"]').html('<div id="spinner" class="spinner-border " style="vertical-align: middle;" role="status" aria-hidden="true"></div>');
        getcriterioGarantes(promise, element);

      });
    }
  });
}
export function datapicker(id, fechamin, fechamax, fechaini) {
  $("#" + id).daterangepicker({
    singleDatePicker: true,
    forceUpdate: true,
    autoUpdateInput: true,
    autoApply: true,
    showDropdowns: true,
    maxDate: fechamax,
    minDate: fechamin,
    startDate: fechaini,
    locale: {
      separator: "   |   ",
      format: "YYYY-MM-DD",
      applyLabel: "Seleccionar",
      cancelLabel: "cancelar",
      fromLabel: "Desde",
      toLabel: "Hasta",
      customRangeLabel: "Seleccionar rango",
      daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
      monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre",
        "Diciembre"
      ],
      firstDay: 1
    }

  });

}
export function datatime(id) {
  $("#" + id).datetimepicker({
    autoclose: 1,
    startView: 3,
    minView: 3,
    pickerPosition: 'bottom-right',
    format: 'MM - yyyy'
  });
}


export function getdatapicker(id) {
  return $("#" + id).val();
}



export function _mg3612152_215(promise) {
  var valuehtml = '';
  promise.forEach(function (value) {
    valuehtml += '<div class="col-md-12 row " style="justify-content: center;margin-top: 5px;">' +
      '<div class="col-md-1" style=" text-align: center;border: 1px solid gray; padding: 5px;">' +
      '<img   src="storage/socio/' + ((value.rutafoto != null) ? value.rutafoto : 'avatar.png') + '"  class="rounded-circle fotosociomini" alt="Cinque Terre">' +
      '  </div>' +
      '<div class="col-md-5 input-group" style="align-items: center;border: 1px solid gray; padding: 5px;">' +
      '<p style="margin:0">' + value.nomgrado + ' ' + value.nombre + ' ' + value.apaterno + ' ' + value.amaterno + '</p>' +
      '<p class="col-md-12" style="margin:0;text-align: right;">Factor obtenido: ' + value.factorg + '%</p>' +
      '</div>' +
      '</div>';
  });
  return valuehtml.length > 0 ? ' <div class="form-group row "><p class="col-md-12 titulo" >Datos de los Garantes:</p> ' + valuehtml + '</div>' : '';
}

export class Modals {
  constructor() {
    this.modales = new Map();
  }
  addModal(id,funOpen=null,funClose=null) {
    $("#" + id).children(".modal-dialog").addClass("modal-dialog-centered");
    $("#" + id).attr('data-backdrop', 'static');
    $("#" + id).attr('data-keyboard', 'false');
    if (jQuery.isFunction(funOpen)) {
      $("#" + id).on('shown.bs.modal', function () {
        funOpen();
      });
    }
    if (jQuery.isFunction(funClose)) {
      $("#" + id).on('hidden.bs.modal', function () {
        funClose();
      });
    }
    this.modales.set(id, {
      openPrimary: function () {
        $("#" + id).modal("show");
      },
      close: function () {
        $("#" + id).modal("hide");
      }, 
      openInfo: function () {
        $("#" + id).modal({
          backdrop: true,
          keyboard: true
        });
      }
    });
  }

  openModal(id) {
    if (this.modales.has(id)) {
      this.closeOthers(id);
      this.modales.get(id).openPrimary();
    }
  }

  openModalInfo(id) {
    if (this.modales.has(id)) {
      this.closeOthers(id);
      this.modales.get(id).openInfo();
    }
  }

  closeModal(id) {
    if (this.modales.has(id)) {
      this.modales.get(id).close();
    }
  }

  closeOthers(id) {
    for (var [key, data] of this.modales) {
      if (key != id) {
        data.close();
      }
    }
  }
}
export class Slider {
  addSlider(id) {
    $('#' + id).ionRangeSlider();
  }
  getValue(id) {
    return $('#' + id).data().from;
  }
}

// https://simonbengtsson.github.io/jsPDF-AutoTable/
// https://github.com/MrRio/jsPDF
// http://www.rotisedapsales.com/snr/cloud2/website/jsPDF-master/docs/global.html#viewerPreferences
// https://developer.tizen.org/community/tip-tech/creating-pdf-documents-jspdf
// https://github.com/simonbengtsson/jsPDF-AutoTable
// ejemplos http://raw.githack.com/MrRio/jsPDF/master/#
//https://rawgit.com/MrRio/jsPDF/master/docs/jsPDF.html#line
//lineas en la tabla https://stackoverflow.com/questions/55293032/how-to-give-borders-to-jspdf-autotable-content-generated-dynamically-as-in-templ

function imgToBase64(src, callback) {
  var outputFormat = src.substr(-3) === 'png' ? 'image/png' : 'image/jpeg';
  var img = new Image();
  img.crossOrigin = 'Anonymous';
  img.onload = function () {
    var canvas = document.createElement('CANVAS');
    var ctx = canvas.getContext('2d');
    var dataURL;
    canvas.height = this.naturalHeight;
    canvas.width = this.naturalWidth;
    ctx.drawImage(this, 0, 0);
    dataURL = canvas.toDataURL(outputFormat);
    callback(dataURL);
  };
  img.src = src;
  if (img.complete || img.complete === undefined) {
    img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
    img.src = src;
  }
}

function centrarText(doc, texto, posvertical) {
  var pageSize = doc.internal.pageSize;
  var pageWidth = pageSize.width ? pageSize.width : pageSize.getWidth();
  var dime = doc.getTextDimensions(texto);
  doc.text(texto, (pageWidth / 2) - (dime.w / 2), posvertical);
}
function centrarTextTo(doc, texto,poshorizontal, posvertical) { 
  var pageWidth = 5.3;
  var dime = doc.getTextDimensions(texto);
  var nuevaposH=((pageWidth / 2) - (dime.w / 2))+poshorizontal; 
  doc.text(texto, nuevaposH, posvertical);
}
function centrarTextTo2(doc, texto,poshorizontal, posvertical) { 
  var pageWidth = 5.5;
  var dime = doc.getTextDimensions(texto);
  var nuevaposH=posvertical-((pageWidth / 2) - (dime.w / 2)); 
  doc.text(texto, poshorizontal, nuevaposH,null,90);
}
function textToBase64Barcode(text){
  var canvas = document.createElement("canvas");
  JsBarcode(canvas, text, {format: "CODE39",displayValue:false});
  return canvas.toDataURL("image/png");
}

export function _vvp2521_cr01(ta,fotocr,funn, idview = 'planout') {
    $("#" + idview).attr("src",''); 
    $("#2" + idview).attr("src",''); 
    let fondo=fotocr.foto; 
    let fondodos=fotocr.fotoa; 
    imgToBase64(ta.rutafoto?'storage/socio/'+ta.rutafoto:fotocr.avatar, function (fotosocio) {
      var qr = new QRious();  
      qr.value =(ta.codsocio?ta.codsocio:'')+'|'+(ta.carnetmilitar?ta.carnetmilitar:'')+'|'+ta.numpapeleta;
      qr.mime = 'image/jpeg';
      // var doc = new jsPDF('l', 'mm', [86,55]); //216mm X 279mm (carta)
      var doc = new jsPDF('p', 'cm','a4'); //216mm X 279mm (carta)
      doc.setProperties({
        title: 'Carnet socio'
      }); 
      doc.addImage(fondo, 'JPEG',3.2,0.9, 8.6, 5.5); 
        doc.addImage(fotosocio, 'JPEG', 3.99, 2.37, 2.31, 2.31); 
         doc.setFontSize(10);
         doc.setFontStyle('bold');
           doc.setTextColor(52,52,52);
          centrarTextTo(doc, (ta.nomgrado+' '+ta.nomespecialidad)?ta.nomgrado+' '+ta.nomespecialidad:'___', 6.5,2.7); 
          centrarTextTo(doc, ta.nombre?ta.nombre:'___', 6.5,3.1); 
          centrarTextTo(doc,((ta.apaterno+" "+ta.amaterno)?ta.apaterno+" "+ta.amaterno:'______'), 6.5,3.5);
       doc.setFontStyle('normal');
       doc.setFontSize(7);
       doc.text(ta.nomfuerza,7, 4.55); 
       doc.text(ta.fechanacimiento?( moment(ta.fechanacimiento).format("DD/MM/YYYY")):'___', 7, 5.35); 
       doc.text(ta.nomtiposocio?ta.nomtiposocio:'___', 9.65, 4.55); 
       doc.text((ta.ci?ta.ci:'___')+' '+(ta.abrvdep?ta.abrvdep:'__'), 9.65, 5.35); 
      doc.setTextColor(255,255,255);
      doc.setFontSize(6);
      doc.text(ta.codsocio?ta.codsocio:'___', 4.54, 5.1); 
      doc.text(ta.carnetmilitar?ta.carnetmilitar:'___', 4.54, 5.34); 
      doc.addImage(textToBase64Barcode(ta.numpapeleta?ta.numpapeleta:'0'), 'JPEG',8.5, 5.8, 3,0.5);  
      $("#" + idview).attr("src", doc.output('datauristring')); 

      var doc2 = new jsPDF('p', 'cm','a4');  
      doc2.setProperties({
        title: 'Carnet socio posterior'
      }); 
      doc2.addImage(fondodos, 'JPEG',3.2,0.9, 8.6, 5.5); 
         
      doc2.addImage(qr.toDataURL(), 'JPEG', 8.62, 2.37, 2.18, 2.18); 
      $("#2" + idview).attr("src", doc2.output('datauristring')); 
 
      funn();
    }); 
  }
  export function _vvp2521_cr02_b(ta,fotocr,funn, idview = 'planout') {
    $("#" + idview).attr("src",''); 
    $("#2" + idview).attr("src",''); 
    let fondo=fotocr.foto; 
    let fondodos=fotocr.fotoa;  
      let fotosocio = fotocr.avatar;
      var qr = new QRious();  
      qr.value =(ta.codsocio?ta.codsocio:'')+'|'+(ta.ci?ta.ci:'')+'|'+ta.numpapeleta;
      qr.mime = 'image/jpeg';
      // var doc = new jsPDF('l', 'mm', [86,55]); //216mm X 279mm (carta)
      var doc = new jsPDF('p', 'cm','a4'); //216mm X 279mm (carta)
      doc.setProperties({
        title: 'Carnet beneficiario'
      }); 
      doc.addImage(fondo, 'JPEG',3.17,0.9, 8.6, 5.5); 
      doc.addImage(fotosocio, 'JPEG', 6.95, 2.5, 2.31, 2.31,'socio','NONE',90);  
      doc.setFontSize(10);
      doc.setFontStyle('bold');
      doc.setTextColor(52,52,52);
      
          // centrarTextTo2(doc, (ta.nombre)?ta.nombre:'___', 8.2,6.4); 
          centrarTextTo2(doc, ta.nombre?ta.nombre:'___', 7.8,6.4); 
          centrarTextTo2(doc,((ta.apaterno+" "+ta.amaterno)?ta.apaterno+" "+ta.amaterno:'______'), 8.2,6.4);
            
      doc.setFontStyle('normal');
      doc.setFontSize(7); 
      doc.text((ta.codsocio?ta.codsocio:''),9.1, 5.9,null,90); 
      doc.text(ta.parentesco?ta.parentesco:'___', 9.1, 3.15,null,90); 

      doc.text(ta.fechanac?( moment(ta.fechanac).format("DD/MM/YYYY")):'___', 9.93, 5.9,null,90);
      doc.text((ta.ci?ta.ci:'___')+' '+(ta.abrvdep?ta.abrvdep:'__'), 9.93, 3.15,null,90);
       
      doc.setFontSize(6);
     
      centrarTextTo2(doc, (ta.idtiposocio==1)?'BENEFICIARIO(A)':'BENEFICIARIO(A) - SP', 10.77,6.4);
      doc.addImage(textToBase64Barcode(ta.numpapeleta?ta.numpapeleta:'0'), 'JPEG',11.3, 4.6, 3,0.5,'barra','NONE',90);   
      doc.setFontSize(5);
      doc.setTextColor(255,255,255);
      centrarTextTo2(doc, ( ('Válido hasta Diciembre - '+ moment().add(1, 'years').format("YYYY")).toUpperCase()), 11.5,6.4); 

      $("#" + idview).attr("src", doc.output('datauristring')); 

      var doc2 = new jsPDF('p', 'cm','a4');  
      doc2.setProperties({
        title: 'Carnet beneficiario posterior'
      }); 
      doc2.addImage(fondodos, 'JPEG',3.17,0.9, 8.6, 5.5);   
      doc2.addImage(qr.toDataURL(), 'JPEG', 6.97, 2.5, 2.31, 2.31,'socio','NONE',90);  
      $("#2" + idview).attr("src", doc2.output('datauristring')); 
 
      funn();
    
  }
  export function _vvp2521_cr02(ta,fotocr,funn, idview = 'planout') {
    $("#" + idview).attr("src",''); 
    $("#2" + idview).attr("src",''); 
    let fondo=fotocr.foto; 
    let fondodos=fotocr.fotoa; 
    imgToBase64(ta.rutafoto?'storage/socio/'+ta.rutafoto:fotocr.avatar, function (fotosocio) {
      var qr = new QRious();  
      qr.value =(ta.codsocio?ta.codsocio:'')+'|'+(ta.carnetmilitar?ta.carnetmilitar:'')+'|'+ta.numpapeleta;
      qr.mime = 'image/jpeg';
      // var doc = new jsPDF('l', 'mm', [86,55]); //216mm X 279mm (carta)
      var doc = new jsPDF('p', 'cm','a4'); //216mm X 279mm (carta)
      doc.setProperties({
        title: 'Carnet socio'
      }); 
      doc.addImage(fondo, 'JPEG',3.17,0.9, 8.6, 5.5); 
      doc.addImage(fotosocio, 'JPEG', 6.95, 2.5, 2.31, 2.31,'socio','NONE',90);  
      doc.setFontSize(10);
      doc.setFontStyle('bold');
      doc.setTextColor(52,52,52);
      if(ta.idfuerza==5){// solo valida fuera armada para imprimir la abreviacion
        centrarTextTo2(doc, (ta.abrev+' '+ta.nomespecialidad)?ta.abrev+' '+ta.nomespecialidad:'___', 8.05,6.4); 
      }else{
        centrarTextTo2(doc, (ta.nomgrado+' '+ta.nomespecialidad)?ta.nomgrado+' '+ta.nomespecialidad:'___', 8.05,6.4); 
      } 
          centrarTextTo2(doc, ta.nombre?ta.nombre:'___', 8.45,6.4); 
          centrarTextTo2(doc,((ta.apaterno+" "+ta.amaterno)?ta.apaterno+" "+ta.amaterno:'______'), 8.85,6.4);
            
      doc.setFontStyle('normal');
      doc.setFontSize(7); 
      doc.text(ta.nomfuerza,9.7, 5.9,null,90); 
      doc.text(ta.fechanacimiento?( moment(ta.fechanacimiento).format("DD/MM/YYYY")):'___', 10.53, 5.9,null,90);
      doc.text(ta.nomtiposocio?ta.nomtiposocio:'___', 9.7, 3.15,null,90); 
      doc.text((ta.ci?ta.ci:'___')+' '+(ta.abrvdep?ta.abrvdep:'__'), 10.53, 3.15,null,90);
       
      doc.setFontSize(6);
      doc.text(ta.codsocio?ta.codsocio:'___', 7.24, 4.1,null,90);
      doc.text(ta.carnetmilitar?ta.carnetmilitar:'___', 7.46, 4.1,null,90);
      centrarTextTo2(doc, (ta.idtiposocio==1)?'TITULAR':'TITULAR - SP', 10.87,6.4);
       doc.addImage(textToBase64Barcode(ta.numpapeleta?ta.numpapeleta:'0'), 'JPEG',11.4, 4.7, 3,0.5,'barra','NONE',90);   

      // centrarTextTo2(doc, (ta.idtiposocio==1)?'TITULAR':'TITULAR - SP', 10.77,6.4);
      // doc.addImage(textToBase64Barcode(ta.numpapeleta?ta.numpapeleta:'0'), 'JPEG',11.3, 4.6, 3,0.5,'barra','NONE',90);   
      doc.setFontSize(5);
      doc.setTextColor(255,255,255);
      centrarTextTo2(doc, ( ('Válido hasta Diciembre - '+ moment().add(3, 'years').format("YYYY")).toUpperCase()), 11.63,6.4); 

  
      $("#" + idview).attr("src", doc.output('datauristring'));  
      var doc2 = new jsPDF('p', 'cm','a4');  
      doc2.setProperties({
        title: 'Carnet socio posterior'
      }); 
      doc2.addImage(fondodos, 'JPEG',3.17,0.9, 8.6, 5.5);   
      doc2.addImage(qr.toDataURL(), 'JPEG', 6.97, 2.5, 2.31, 2.31,'socio','NONE',90);  
      $("#2" + idview).attr("src", doc2.output('datauristring')); 
 
      funn();
    }); 
  }
  export function _vvp2521_cr0egresados(ta,fotocr,funn, idview = 'planout') {
    $("#" + idview).attr("src",''); 
    $("#2" + idview).attr("src",''); 
    let fondo=fotocr.foto; 
    let fondodos=fotocr.fotoa; 
    imgToBase64(ta.rutafoto?'storage/socio/e/'+ta.rutafoto:fotocr.avatar, function (fotosocio) {
      var qr = new QRious();  
      qr.value =(ta.codsocio?ta.codsocio:'')+'|'+(ta.carnetmilitar?ta.carnetmilitar:'')+'|'+ta.numpapeleta;
      qr.mime = 'image/jpeg';
      // var doc = new jsPDF('l', 'mm', [86,55]); //216mm X 279mm (carta)
      var doc = new jsPDF('p', 'cm','a4'); //216mm X 279mm (carta)
      doc.setProperties({
        title: 'Carnet socio'
      }); 
      doc.addImage(fondo, 'JPEG',3.17,0.9, 8.6, 5.5); 
      doc.addImage(fotosocio, 'JPEG', 6.95, 2.5, 2.31, 2.31,'socio','NONE',90);  
      doc.setFontSize(10);
      doc.setFontStyle('bold');
      doc.setTextColor(52,52,52);
      if(ta.idfuerza==5){// solo valida fuera armada para imprimir la abreviacion
        centrarTextTo2(doc, (ta.abrev+' '+ta.nomespecialidad)?ta.abrev+' '+ta.nomespecialidad:'___', 8.05,6.4); 
      }else{
        centrarTextTo2(doc, (ta.nomgrado+' '+ta.nomespecialidad)?ta.nomgrado+' '+ta.nomespecialidad:'___', 8.05,6.4); 
      } 
          centrarTextTo2(doc, ta.nombre?ta.nombre:'___', 8.45,6.4); 
          centrarTextTo2(doc,((ta.apaterno+" "+ta.amaterno)?ta.apaterno+" "+ta.amaterno:'______'), 8.85,6.4);
            
      doc.setFontStyle('normal');
      doc.setFontSize(7); 
      doc.text(ta.nomfuerza,9.7, 5.9,null,90); 
      doc.text(ta.fechanacimiento?( moment(ta.fechanacimiento).format("DD/MM/YYYY")):'___', 10.53, 5.9,null,90);
      doc.text(ta.nomtiposocio?ta.nomtiposocio:'___', 9.7, 3.15,null,90); 
      doc.text((ta.ci?ta.ci:'___')+' '+(ta.abrvdep?ta.abrvdep:'__'), 10.53, 3.15,null,90);
       
      doc.setFontSize(6);
      doc.text(ta.codsocio?ta.codsocio:'___', 7.24, 4.1,null,90);
      doc.text(ta.carnetmilitar?ta.carnetmilitar:'___', 7.46, 4.1,null,90);
      centrarTextTo2(doc, (ta.idtiposocio==1)?'TITULAR':'TITULAR - SP', 10.87,6.4);
       doc.addImage(textToBase64Barcode(ta.numpapeleta?ta.numpapeleta:'0'), 'JPEG',11.4, 4.7, 3,0.5,'barra','NONE',90);   

      // centrarTextTo2(doc, (ta.idtiposocio==1)?'TITULAR':'TITULAR - SP', 10.77,6.4);
      // doc.addImage(textToBase64Barcode(ta.numpapeleta?ta.numpapeleta:'0'), 'JPEG',11.3, 4.6, 3,0.5,'barra','NONE',90);   
      doc.setFontSize(5);
      doc.setTextColor(255,255,255);
      centrarTextTo2(doc, ( ('Válido hasta Diciembre - '+ moment().add(3, 'years').format("YYYY")).toUpperCase()), 11.63,6.4); 

  
      $("#" + idview).attr("src", doc.output('datauristring'));  
      var doc2 = new jsPDF('p', 'cm','a4');  
      doc2.setProperties({
        title: 'Carnet socio posterior'
      }); 
      doc2.addImage(fondodos, 'JPEG',3.17,0.9, 8.6, 5.5);   
      doc2.addImage(qr.toDataURL(), 'JPEG', 6.97, 2.5, 2.31, 2.31,'socio','NONE',90);  
      $("#2" + idview).attr("src", doc2.output('datauristring')); 
 
      funn();
    }); 
  }
  export function _vvp2521_cr_emp(ta,fotocr,idview = 'contenedorframes',finn) { 
    let fondo=fotocr.foto; 
    let fondodos=fotocr.fotoa; 
    let fotosocio=fotocr.avatar;  
      var qr = new QRious();  
      qr.value =(ta.codempleado?ta.codempleado:'')+'|'+(ta.ci?ta.ci:'')+'|'+(ta.nomcargo?ta.nomcargo:'');
      qr.mime = 'image/jpeg'; 
      var doc = new jsPDF('p', 'cm','a4'); 
      doc.setProperties({
        title: 'Credencial'
      }); 
      doc.addImage(fondo, 'JPEG',3.17,0.9, 8.6, 5.5); 
      doc.addImage(fotosocio, 'JPEG', 6.95, 2.5, 2.31, 2.31,'socio','NONE',90);  
      doc.setFontSize(9);
      doc.setFontStyle('normal');
      doc.setTextColor(52,52,52);
      if(ta.nomprofesion){
        centrarTextTo2(doc, ta.nomprofesion?ta.nomprofesion:'', 8.1,6.4); 
        centrarTextTo2(doc, ta.nombre?ta.nombre:'___', 8.5,6.4); 
        centrarTextTo2(doc,((ta.apaterno+" "+ta.amaterno)?ta.apaterno+" "+ta.amaterno:'___'), 8.9,6.4);
      }else{
          centrarTextTo2(doc, ta.nombre?ta.nombre:'___', 8.3,6.4); 
          centrarTextTo2(doc,((ta.apaterno+" "+ta.amaterno)?ta.apaterno+" "+ta.amaterno:'___'), 8.7,6.4);
      }
         
      doc.setFontSize(6);
      doc.text(ta.ci?ta.ci+' '+(ta.abrvdep?ta.abrvdep:''):'0', 7.24, 4.1,null,90);
      doc.text(ta.codempleado?ta.codempleado:'-', 7.46, 4.1,null,90);   

      doc.setFontStyle('bold');
      doc.setFontSize(11);   
      var splitText = doc.splitTextToSize(ta.nomcargo.replace(/[^a-zA-ZÀ-ÿ\u00f1\u00d1 0-9.]+/g,' ').toUpperCase(), 4.7); 
      var posi=splitText.length==1?10:(splitText.length==2?9.73:(splitText.length>=3?9.63:9.63));
        _.forEach(splitText, function(value) {
          centrarTextTo2(doc, value,posi,6.4); 
          posi+=0.4;
        });
      
      doc.setFontSize(6); 
      centrarTextTo2(doc,'TITULAR', 10.87,6.4);
      doc.addImage(textToBase64Barcode(ta.codempleado?ta.codempleado:'0'), 'JPEG',11.4, 4.7, 3,0.5,'barra','NONE',90);    
      doc.setFontSize(5);
      doc.setTextColor(255,255,255);
      // centrarTextTo2(doc, ( ('Válido hasta Diciembre - '+ moment().format("YYYY")).toUpperCase()), 11.63,6.4); 
      centrarTextTo2(doc, ( ('Válido hasta Diciembre - '+ moment().add(1, 'years').format("YYYY")).toUpperCase()), 11.63,6.4); 




 
      var diva= $('<div>')
      .css('padding','0')
      .addClass( "col-md-6");


      var cre1=$('<iframe>')                    
      .attr('src', doc.output('datauristring')) 
      .attr('height','500px')
      .attr('width','100%') ;
      cre1.appendTo(diva);

      var botones1= $('<div>').css('bottom','0') 
      .css('position','absolute')
      .css('margin','3%');

     $('<button/>') 
      .addClass( "btn btn-primary") 
      .text('Ampliar')
      .click(function () { window.open(doc.output('bloburl'), '_blank',"fullscreen=yes, scrollbars=auto"); })
      .appendTo(botones1);

      $('<button/>').addClass( "btn btn-primary") 
      .css('margin-left','7px')
      .text('Recargar')
      .click(function () { cre1.attr('src', doc.output('datauristring'));})
      .appendTo(botones1);

      botones1.appendTo(diva);

       
  
      diva.appendTo('#'+idview);

      var doc2 = new jsPDF('p', 'cm','a4');  
      doc2.setProperties({
        title: 'Credencial posterior'
      });  
      doc2.addImage(fondodos, 'JPEG',3.17,0.9, 8.6, 5.5);   
      doc2.addImage(qr.toDataURL(), 'JPEG', 6.97, 2.5, 2.31, 2.31,'socio','NONE',90);  
     
      var divv= $('<div>').css('padding','0').addClass( "col-md-6");
      var cre=$('<iframe>')                    
      .attr('src', doc2.output('datauristring')) 
      .attr('height','500px')
      .attr('width','100%') ;
      cre.appendTo(divv);

      var botones= $('<div>').css('bottom','0') 
       .css('position','absolute')
       .css('margin','3%');

      $('<button/>') 
       .addClass( "btn btn-primary") 
       .text('Ampliar')
       .click(function () { window.open(doc2.output('bloburl'), '_blank',"fullscreen=yes, scrollbars=auto"); })
       .appendTo(botones);

       $('<button/>').addClass( "btn btn-primary") 
       .css('margin-left','7px')
       .text('Recargar')
       .click(function () { cre.attr('src', doc2.output('datauristring'));})
       .appendTo(botones);

       botones.appendTo(divv); 
      divv.appendTo('#'+idview);
      finn();
  }
  export function _vvp2521_cr_cen_emp(ta,fotocr,idview = 'contenedorframes') { 
    let fondo=fotocr.foto; 
    let fondodos=fotocr.fotoa; 
    let fotosocio=fotocr.avatar;  
      var qr = new QRious();  
      qr.value =(ta.codsocio?ta.codsocio:'')+'|'+(ta.abrvdep?ta.abrvdep:'')+'|'+(ta.carnetmilitar?ta.carnetmilitar:'')+'|'+ta.cargo;
      qr.mime = 'image/jpeg'; 
      var doc = new jsPDF('p', 'cm','a4'); 
      doc.setProperties({
        title: 'Credencial'
      }); 
      doc.addImage(fondo, 'JPEG',3.17,0.9, 8.6, 5.5); 
      doc.addImage(fotosocio, 'JPEG', 6.95, 2.5, 2.31, 2.31,'socio','NONE',90);  
      doc.setFontSize(9);
      doc.setFontStyle('normal');
      doc.setTextColor(52,52,52);
          centrarTextTo2(doc, (ta.nomgrado+' '+ta.nomespecialidad)?ta.nomgrado+' '+ta.nomespecialidad:'___', 8.2,6.4); 
          centrarTextTo2(doc, ta.nombre?ta.nombre:'___', 8.6,6.4); 
          centrarTextTo2(doc,((ta.apaterno+" "+ta.amaterno)?ta.apaterno+" "+ta.amaterno:'___'), 9,6.4);
      
          doc.setFontSize(6);
      doc.text(ta.ci?ta.ci+' '+(ta.abrvdep?ta.abrvdep:''):'0', 7.24, 4.1,null,90);
      doc.text(ta.carnetmilitar?ta.carnetmilitar:'-', 7.46, 4.1,null,90);   

      doc.setFontStyle('bold');
      doc.setFontSize(11);   

        var posi=9.8;
        var splitText = doc.splitTextToSize(ta.cargo.replace(/[^a-zA-ZÀ-ÿ\u00f1\u00d1 0-9.]+/g,' '), 4.8);
        _.forEach(splitText, function(value) {
          centrarTextTo2(doc, value,posi,6.4); 
          posi+=0.4;
        });
 
      doc.setFontSize(6);
      centrarTextTo2(doc, (ta.validate?('Válido hasta '+ (moment(ta.validate).format("MMMM - YYYY")).toUpperCase()):' '), 10.8,6.4); 
      doc.addImage(textToBase64Barcode(ta.codsocio?ta.codsocio:'0'), 'JPEG',11.4, 4.6, 3,0.5,'barra','NONE',90);   
 
 
      var diva= $('<div>')
      .css('padding','0')
      .addClass( "col-md-6");


      var cre1=$('<iframe>')                    
      .attr('src', doc.output('datauristring')) 
      .attr('height','500px')
      .attr('width','100%') ;
      cre1.appendTo(diva);

      var botones1= $('<div>').css('bottom','0') 
      .css('position','absolute')
      .css('margin','3%');

     $('<button/>') 
      .addClass( "btn btn-primary") 
      .text('Ampliar')
      .click(function () { window.open(doc.output('bloburl'), '_blank',"fullscreen=yes, scrollbars=auto"); })
      .appendTo(botones1);

      $('<button/>').addClass( "btn btn-primary") 
      .css('margin-left','7px')
      .text('Recargar')
      .click(function () { cre1.attr('src', doc.output('datauristring'));})
      .appendTo(botones1);

      botones1.appendTo(diva);

       
  
      diva.appendTo('#'+idview);

      var doc2 = new jsPDF('p', 'cm','a4');  
      doc2.setProperties({
        title: 'Credencial posterior'
      });  
      doc2.addImage(fondodos, 'JPEG',3.17,0.9, 8.6, 5.5);   
      doc2.addImage(qr.toDataURL(), 'JPEG', 6.97, 2.5, 2.31, 2.31,'socio','NONE',90);  
     
      var divv= $('<div>').css('padding','0').addClass( "col-md-6");
      var cre=$('<iframe>')                    
      .attr('src', doc2.output('datauristring')) 
      .attr('height','500px')
      .attr('width','100%') ;
      cre.appendTo(divv);

      var botones= $('<div>').css('bottom','0') 
       .css('position','absolute')
       .css('margin','3%');

      $('<button/>') 
       .addClass( "btn btn-primary") 
       .text('Ampliar')
       .click(function () { window.open(doc2.output('bloburl'), '_blank',"fullscreen=yes, scrollbars=auto"); })
       .appendTo(botones);

       $('<button/>').addClass( "btn btn-primary") 
       .css('margin-left','7px')
       .text('Recargar')
       .click(function () { cre.attr('src', doc2.output('datauristring'));})
       .appendTo(botones);

       botones.appendTo(divv); 
      divv.appendTo('#'+idview);
  }
 

  export function _vvp2521_bio01(fotocr,funn, idview = 'biometricoframe') {
    $("#" + idview).attr("src",'');   
    let fondo=fotocr.foto;  
      var doc = new jsPDF('p', 'cm','a4'); //216mm X 279mm (carta)
      doc.setProperties({
        title: 'Carnet asistencia'
      }); 
      doc.addImage(fondo, 'JPEG',3.17,0.9, 8.6, 5.5); 
      
      $("#" + idview).attr("src", doc.output('datauristring')); 
 
      funn();
    z
  }
export function _vvp2521_00001(ta, idview = 'planout') { 
  imgToBase64('img/iconad.png', function (base64) {

    var doc = new jsPDF('p', 'mm', 'letter'); //216mm X 279mm (carta)
    doc.setProperties({
      title: 'Plan De Pagos'
    });
    doc.addImage(base64, 'JPEG', 14, 14, 32, 20);


    doc.setFontSize(9);
    centrarText(doc, "ASOCIACION NACIONAL DE SUBOFICIALES Y SARGENTOS", 20);
    centrarText(doc, "DE LAS FUERZAS ARMADAS", 25);
    doc.setFontSize(11);
    centrarText(doc, '"ASCINALSS"', 30);
    doc.setFontSize(12);
    doc.setFontStyle('bold');
    centrarText(doc, '"PLAN DE PAGOS"', 40); 
    doc.autoTable({
      head: [{
        pe: 'Periodo',
        fp: 'Fecha de Pago',
        di: 'Dias',
        am: 'Capital',
        in: 'Interes',
        indi: 'Interes Diferido',
        seg: 'Seguro',
        car: 'Cargos',
        cu: 'Total Cuota',
        ca: 'Saldo'
      }],
      body: generateBodyTable(ta),
      foot: [{
        pe: {
          content: 'Totales : ',
          colSpan: 3,
          styles: {
            halign: 'right'
          }
        },
        car: getTotal(ta, 'car'),
        cu: getTotal(ta, 'cut'),
        in: getTotal(ta, 'in'),
        indi: getTotal(ta, 'indi'),
        seg: getTotal(ta, 'seg'),
        am: getTotal(ta, 'am'),
        ca: {
          content: ''
        }
      }],
      theme: 'grid',
      styles: {
        lineColor: [211, 211, 211],
        lineWidth: 0.2,
        halign: 'center'
      },
      headStyles: {
        fillColor: [203, 207, 212],
        textColor: [0, 0, 0],
        fontStyle: 'bold'
      },
      footStyles: {
        fillColor: [231, 234, 238],
        textColor: [0, 0, 0],
        fontStyle: 'normal'
      },
      showFoot: 'lastPage',
      showHead: 'firstPage',
      startY: 51
    }); 
    $("#" + idview).attr("src", doc.output('datauristring'));
  });
}
export function _vvp2521_00002(ta, idview = 'planout') {
  imgToBase64('img/iconad.png', function (base64) {

    var doc = new jsPDF('p', 'mm', 'letter'); //216mm X 279mm (carta)
    doc.setProperties({
      title: 'Cargar Ascii'
    });
      
    var positicon=0;
    doc.autoTable({
      head: [{
        no: 'No.',
        num: 'Papeleta',
        gra: 'Grado',
        nom: 'Nombre',
        est: 'Estado',
        mon: 'Moneda',
        cut: 'Cuota',
        pla: 'Plzo'
      }],
      body: generateBodyTablepdfDetalle(JSON.parse(ta.file)),
       
      theme: 'grid',
      bodyStyles: { 
        textColor: [0, 0, 0],
        lineColor: [211, 211, 211],
        lineWidth: 0,
        halign: 'center',
        cellPadding:1,
        fontSize:8,
        cellPadding:0.5
      },
      headStyles: {
        fillColor: [203, 207, 212],
        textColor: [0, 0, 0],
        fontStyle: 'bold',
        halign: 'center',
        fontSize:10 
      },  
      showHead: 'everyPage',
      startY: 40,
      margin:{top: 40},
      didDrawCell: data => {
         
        if (data.section === 'body' && data.column.index === 'no'&&(data.row.cells.no.colSpan==6)) {  
          positicon=data.row.cells.no.y;
          doc.setDrawColor(0, 0, 0);
          doc.setLineWidth(0.3);
          doc.line(data.row.cells.no.x, data.row.cells.no.y, data.row.cells.no.x + data.row.cells.no.width, data.row.cells.no.y); 
        }
        if (data.section === 'body' && data.column.index === 'cut'&&positicon==data.row.cells.cut.y) {  
          doc.setDrawColor(0, 0, 0);
          doc.setLineWidth(0.3);
          doc.line(data.row.cells.cut.x, data.row.cells.cut.y, data.row.cells.cut.x + data.row.cells.cut.width, data.row.cells.cut.y); 
        }
        if (data.section === 'body' && data.column.index === 'pla'&&positicon==data.row.cells.pla.y) {  
          doc.setDrawColor(0, 0, 0);
          doc.setLineWidth(0.3);
          doc.line(data.row.cells.pla.x, data.row.cells.pla.y, data.row.cells.pla.x + data.row.cells.pla.width, data.row.cells.pla.y); 
        }
      }
    }); 


    var pageCount = doc.internal.getNumberOfPages();
            for(var i = 0; i < pageCount; i++) { 
              doc.setPage(i);  
              doc.addImage(base64, 'JPEG', 14, 14, 32, 20); 
              doc.setFontSize(12);
              doc.setFontStyle('bold');
              centrarText(doc, '"DETALLE DE ALTAS POR PRESTAMOS"', 25); 
              doc.setFontSize(9); 
              doc.setFontStyle('normal');
              centrarText(doc, "Correspondiente a "+(JSON.parse(ta.file)).mes+" de "+(JSON.parse(ta.file)).anio, 29); 
              doc.text(ta.fecha, 170, 24);
              doc.setFontSize(8); 
              doc.text('Pagina '+doc.internal.getCurrentPageInfo().pageNumber + " de " + pageCount, 170, 27);
            }
    $("#" + idview).attr("src", doc.output('datauristring'));
  });
}
export function _vvp2521_00003(ta, idview = 'planout') {
  imgToBase64('img/iconad.png', function (base64) {

    var doc = new jsPDF('p', 'mm', 'letter'); //216mm X 279mm (carta)
    doc.setProperties({
      title: 'Cargar Ascii'
    });
      
    var positicon=0;
    doc.autoTable({
      head: [{
        fue: 'Fuerza',
        cod: 'Codigo',
        can: 'Cantidad',
        mon: 'Monto Total'
      }],
      body: generateBodyTablepdfResumen(JSON.parse(ta.file)),
       
      theme: 'grid',
      bodyStyles: { 
        textColor: [0, 0, 0],
        lineColor: [211, 211, 211],
        lineWidth: 0,
        halign: 'center',
        cellPadding:1,
        fontSize:8,
        cellPadding:0.5
      },
      headStyles: {
        fillColor: [203, 207, 212],
        textColor: [0, 0, 0],
        fontStyle: 'bold',
        halign: 'center',
        fontSize:10 
      },  
      showHead: 'everyPage',
      startY: 40,
      margin:{top: 40},
      didDrawCell: data => {
         
        if (data.section === 'body' && data.column.index === 'fue'&&(data.row.cells.fue.colSpan==2)) {  
          positicon=data.row.cells.fue.y;
          doc.setDrawColor(0, 0, 0);
          doc.setLineWidth(0.3);
          doc.line(data.row.cells.fue.x, data.row.cells.fue.y, data.row.cells.fue.x + data.row.cells.fue.width, data.row.cells.fue.y); 
        }
        if (data.section === 'body' && data.column.index === 'can'&&positicon==data.row.cells.can.y) {  
          doc.setDrawColor(0, 0, 0);
          doc.setLineWidth(0.3);
          doc.line(data.row.cells.can.x, data.row.cells.can.y, data.row.cells.can.x + data.row.cells.can.width, data.row.cells.can.y); 
        }
        if (data.section === 'body' && data.column.index === 'mon'&&positicon==data.row.cells.mon.y) {  
          doc.setDrawColor(0, 0, 0);
          doc.setLineWidth(0.3);
          doc.line(data.row.cells.mon.x, data.row.cells.mon.y, data.row.cells.mon.x + data.row.cells.mon.width, data.row.cells.mon.y); 
        }
      }
    }); 


    var pageCount = doc.internal.getNumberOfPages();
            for(var i = 0; i < pageCount; i++) { 
              doc.setPage(i);  
              doc.addImage(base64, 'JPEG', 14, 14, 32, 20); 
              doc.setFontSize(12);
              doc.setFontStyle('bold');
              centrarText(doc, '"RESUMEN DE ALTAS POR PRESTAMOS"', 25); 
              doc.setFontSize(9); 
              doc.setFontStyle('normal');
              centrarText(doc, "Correspondiente a "+(JSON.parse(ta.file)).mes+" de "+(JSON.parse(ta.file)).anio, 29); 
              doc.text(ta.fecha, 170, 24);
              doc.setFontSize(8); 
              doc.text('Pagina '+doc.internal.getCurrentPageInfo().pageNumber + " de " + pageCount, 170, 27);
            }
    $("#" + idview).attr("src", doc.output('datauristring'));
  });
}
export function _vvp2521_00004(ta,user,date,idview = 'planout') {
  imgToBase64('img/iconad.png', function (base64) {

    var doc = new jsPDF('p', 'mm', 'letter'); //216mm X 279mm (carta)
    doc.setProperties({
      title: 'Reporte de la configuración de perfiles de cuentas'
    });
      
    var positicon=0;
    doc.autoTable({
      head: [{
        nom: 'Nombre del perfil',
        tip: 'tipo',
        mod: 'Modulo',
        deb: {
          content: 'Cuentas',
          colSpan: 2,
          styles: {
            halign: 'center',
            cellWidth:45 
          }
        },
        hab:  {
          content: '', 
          styles: { 
            cellWidth:45 
          }
        } 
      }],
      body: (()=>{ 
        var out=[];
        
          ta.forEach(function(valor) { 
            out.push({ 
              nom:{
                content: '', 
                colSpan: 4
              }
            });
            out.push({
              nom:{
                content: valor.nombre,
                rowSpan: valor.data.length+1,
                styles: {
                  halign: 'left', 
                  valign: 'center' 
                }
              },
              tip:{
                content: valor.tipo,
                rowSpan: valor.data.length+1,
                styles: {
                  halign: 'center', 
                  valign: 'center'   
                }
              },
              mod:{
                content: valor.modulo,
                rowSpan: valor.data.length+1,
                styles: {
                  halign: 'center', 
                  valign: 'center'   
                }
              },
              deb:{
                content: 'Debe',  
                styles: {
                  fillColor: [203, 207, 212],
                  textColor: [0, 0, 0],
                  fontStyle: 'bold',
                  halign: 'center'  
                }
              },
              hab:{
                content: 'Haber',  
                styles: {
                  fillColor: [203, 207, 212],
                  textColor: [0, 0, 0],
                  fontStyle: 'bold',
                  halign: 'center' 
                }
              }
            });
             
            valor.data.forEach(element =>{
                if(element.tipocargo=='d'){
                  out.push({ 
                    deb:{
                      content: element.codcuenta+'-'+element.nomcuenta, 
                      styles: {
                        halign: 'left',  
                        fontSize:6,
                        cellWidth:45 
                      }
                    },
                    hab:{
                      content:'', 
                      styles: {   
                        cellWidth:45 
                      }
                    }
                  });
                }else{
                  out.push({ 
                    deb:{
                      content:'', 
                      styles: {   
                        cellWidth:45 
                      }
                    },hab:{
                      content: element.codcuenta+'-'+element.nomcuenta, 
                      styles: {
                        halign: 'left',  
                        fontSize:6 ,
                        cellWidth:45 
                      }
                    }
                  });

                }
                

            });

            out.push({ 
              nom:{
                content: '', 
                colSpan: 4
              }
            });
            out.push({ 
              nom:{
                content: '', 
                colSpan: 5
              }
            });
            
          });
        return out;
      })(),
       
      theme: 'grid',
      bodyStyles: { 
        textColor: [0, 0, 0],
        lineColor: [211, 211, 211],
        lineWidth: 0,
        halign: 'center',
        cellPadding:1,
        fontSize:8,
        cellPadding:0.5
      },
      headStyles: {
        fillColor: [203, 207, 212],
        textColor: [0, 0, 0],
        fontStyle: 'bold',
        halign: 'center',
        fontSize:10 
      },  
      showHead: 'everyPage',
      startY: 40,
      margin:{top: 40}, 
      didDrawCell: data => {
         
        if (data.section === 'body' && data.column.index === 'nom'&&(data.row.cells.nom.colSpan==5)) {  
          positicon=data.row.cells.nom.y;
          doc.setDrawColor(0, 0, 0);
          doc.setLineWidth(0.3);
          doc.line(data.row.cells.nom.x, data.row.cells.nom.y, data.row.cells.nom.x + data.row.cells.nom.width, data.row.cells.nom.y); 
        } 
      }
    }); 


    var pageCount = doc.internal.getNumberOfPages();
            for(var i = 0; i < pageCount; i++) { 
              doc.setPage(i);  
              doc.addImage(base64, 'JPEG', 14, 14, 32, 20); 
              doc.setFontSize(12);
              doc.setFontStyle('bold');
              centrarText(doc, 'REPORTE DE LA CONFIGURACIÓN ', 25); 
              centrarText(doc, "DE PERFILES DE CUENTAS", 29); 
              doc.setFontSize(9); 
              doc.setFontStyle('normal'); 
              doc.text('Usuario: '+user, 170, 24);
              doc.setFontSize(8); 
              doc.text('Pagina '+doc.internal.getCurrentPageInfo().pageNumber + " de " + pageCount, 170, 27);
              doc.text('Imp.: '+date, 170, 31);
            }
    $("#" + idview).attr("src", doc.output('datauristring'));
  });
}
function generateBodyTablepdfResumen(datas) {
  let body = []; 
   _.forEach(datas.data, function(datageneral) {  
    body.push({ 
    fue: 
    { content: datageneral.nombre, 
      styles: {
        halign: 'left' 
      }
    },
    cod:datageneral.cod,  
    can:datageneral.cantidad,
    mon: 
    { content: datageneral.total, 
      styles: {
        halign: 'right' 
      }
    }
    }); 
    });
    body.push({
      no:{
        content: '',
        colSpan: 8,
        styles: {
          halign: 'left', 
          lineWidth:0,
          fontSize:1,
          cellPadding:1 
        }
      }
    });
    body.push({
      fue:{
        content: 'Total general en Bolivianos',
        colSpan: 2,
        styles: { 
          halign: 'right',
          fontSize:10,
          fontStyle: 'bold',
          cellPadding:1,
          lineWidth:0
        }
      },
      can: 
    { content: datas.cantidad, 
      styles: {
        halign: 'center' ,
        fontStyle: 'bold'
      }
    },
    mon: 
    { content: datas.monto, 
      styles: {
        halign: 'right',
        fontStyle: 'bold'
      }
    }
    });  
   
  return body;
}
function generateBodyTablepdfDetalle(datas) {
  let body = [];
  var numero=0;
   _.forEach(datas.data, function(datageneral) { 
    body.push({
      no:{
        content: '  FUERZA : '+datageneral.nombre,
        colSpan: 8,
        styles: {
          halign: 'left', 
        textColor: [0, 0, 0],
        fontStyle: 'bold',
        fontSize:11, 
        cellPadding:1
        }
      }
    });           
                            _.forEach(datageneral.value, function(datosfuerza) {
                              numero++;
                              body.push({
                              no: numero,
                              num: datosfuerza.numpapeleta,
                              gra: 
                              {
                                content: datosfuerza.grado, 
                                styles: {
                                  halign: 'left' 
                                }
                              },
                              nom:  {
                                content: datosfuerza.nombre, 
                                styles: {
                                  halign: 'left' 
                                }
                              },
                              est: datosfuerza.estado,
                              mon: datosfuerza.moneda,
                              cut: 
                              {
                                content: fillDecimals(datosfuerza.cuota), 
                                styles: {
                                  halign: 'right' 
                                }
                              },
                              pla: datosfuerza.plazo
                              });
                            
                            });
  body.push({
    no:{
      content: '',
      colSpan: 8,
      styles: {
        halign: 'left', 
        lineWidth:0,
        fontSize:1,
        cellPadding:1 
      }
    }
  });
                            body.push({
                              no:{
                                content: 'Total '+datageneral.nombre+' : ',
                                colSpan: 6,
                                styles: { 
                                  halign: 'right',
                                  fontSize:10,
                                  fontStyle: 'bold',
                                  cellPadding:1,
                                  lineWidth:0
                                }
                              },
                              cut:{
                                content: datageneral.total,
                                styles: {
                                  halign: 'right',
                                  cellPadding:1 ,
                                  lineWidth:0
                                }
                              },
                              pla:{
                                content: '',
                                styles: {
                                  halign: 'right',
                                  cellPadding:1 ,
                                  lineWidth:0
                                }
                              }
                            });                
      body.push({
        no:{
          content: '',
          colSpan: 8,
          styles: {
            halign: 'left', 
            lineWidth:0,
            fontSize:5,
            cellPadding:1 
          }
        }
      }); 
    });
   
  return body;
}
 
function generateBodyTable(datas) {
  let body = [];
  for (var valor of datas.values()) {
    body.push({
      pe: valor.pe,
      fp: valor.fp,
      di: valor.di,
      car: redondeo_valor(valor.car),
      cu: redondeo_valor(valor.cut),
      in: redondeo_valor(valor.in),
      indi: redondeo_valor(valor.indi),
      seg: redondeo_valor(valor.seg),
      am: redondeo_valor(valor.am),
      ca: redondeo_valor(valor.ca)
    });
  }
  return body;
}

function getTotal(datas, id) {
  var out = 0;
  for (var valor of datas.values()) {
    out += parseFloat(valor[id]);
  }
  return redondeo_valor(out);
}

export function redondeo_valor(num, decimales = 2, redoncero = true) {
  var signo = (num >= 0 ? 1 : -1);
  num = num * signo;
  if (decimales === 0)
    return signo * Math.round(num);
  num = num.toString().split('e');
  num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
  num = num.toString().split('e');
  if (redoncero) {
    return fillDecimals(signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales)), decimales);
  } else {
    return (signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales)));
  }

}

export function fillDecimals(number, length = 2) {
  function pad(input, length, padding) {
    var str = input + "";
    return (length <= str.length) ? str : pad(str + padding, length, padding);
  }
  var str = number + "";
  var dot = str.lastIndexOf('.');
  var isDecimal = dot != -1;
  var integer = isDecimal ? str.substr(0, dot) : str;
  var decimals = isDecimal ? str.substr(dot + 1) : "";
  decimals = pad(decimals, length, 0);
  return integer + '.' + decimals;
}

export function ppppp() {
  let sumw = new Function('return function foo(a,b) { alert("native function: "+a); return "Hello, serialised world!"+b; }')();
  alert(sumw(1, 2));
}



/******************************************************************************************************************************************* */
/******************************************************************************************************************************************* */
/******************************************************************************************************************************************* */
/******************************************************************************************************************************************* */
/*******************************************************************************************************************************************  
                             
                             _____                                      _                       _         _     
                            |  __ \                                    | |                     | |       | |      
                            | |  \/  ___  _ __   ___  _ __    __ _   __| |  ___   _ __       __| |  ___  | |      
                            | | __  / _ \| '__| / _ \| '_ \  / _` | / _` | / _ \ | '__|     / _` | / _ \ | |      
                            | |_\ \|  __/| |   |  __/| | | || (_| || (_| || (_) || |       | (_| ||  __/ | |      
                             \____/ \___||_|    \___||_| |_| \__,_| \__,_| \___/ |_|        \__,_| \___| |_|      
                                                                                                              
                                                                                                              
                                                 __  _  _                          _           _      _       
                                                / _|(_)| |                        | |         | |    | |      
                             _ __    ___  _ __ | |_  _ | |      ___   ___   _ __  | |_   __ _ | |__  | |  ___ 
                            | '_ \  / _ \| '__||  _|| || |     / __| / _ \ | '_ \ | __| / _` || '_ \ | | / _ \
                            | |_) ||  __/| |   | |  | || |    | (__ | (_) || | | || |_ | (_| || |_) || ||  __/
                            | .__/  \___||_|   |_|  |_||_|     \___| \___/ |_| |_| \__| \__,_||_.__/ |_| \___|
                            | |                                                                               
                            |_|          

                            
                                8888888                               888888                                   .d8888b.                   888                   
                                  888                                   "88b                                  d88P  Y88b                  888                   
                                  888                                    888                                  888    888                  888                   
88888b.   .d88b.  888d888         888   88888b.   .d88b.                 888 888  888  8888b.  88888b.        888         8888b.  888d888 888  .d88b.  .d8888b  
888 "88b d88""88b 888P"           888   888 "88b d88P"88b                888 888  888     "88b 888 "88b       888            "88b 888P"   888 d88""88b 88K      
888  888 888  888 888             888   888  888 888  888                888 888  888 .d888888 888  888       888    888 .d888888 888     888 888  888 "Y8888b. 
888 d88P Y88..88P 888             888   888  888 Y88b 888 d8b            88P Y88b 888 888  888 888  888       Y88b  d88P 888  888 888     888 Y88..88P      X88 
88888P"   "Y88P"  888           8888888 888  888  "Y88888 Y8P            888  "Y88888 "Y888888 888  888        "Y8888P"  "Y888888 888     888  "Y88P"   88888P' 
888                                                   888              .d88P                                                                                    
888                                              Y8b d88P            .d88P"                                                                                     
888                                               "Y88P"            888P"                                                                                       
          
/******************************************************************************************************************************************* */
/******************************************************************************************************************************************* */
/******************************************************************************************************************************************* */
/******************************************************************************************************************************************* */
/******************************************************************************************************************************************* */
/******************************************************************************************************************************************* */
/******************************************************************************************************************************************* */
var abc = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "U", "V", "W", "X", "Y", "Z"];
var funciones = {
  input: function (id, nombre, css) {
    var operador = $('<span v-type="' + id + '" v-data="input" style="' + css + '"><strong style="padding-left: 4px;">' + nombre + '</strong></span>');
    $('<div id="values"><input type="number" placeholder="0" name="input" value="1" autofocus> </div>').appendTo(operador);
    return operador;
  },
  inputSi: function (id, nombre, css) {
    var operador = $('<span v-type="' + id + '" v-data="inputSi" style="' + css + '"><strong style="padding-left: 4px;">' + nombre + '</strong></span>');
    $('<div id="values"><input id="i1" type="number" placeholder="0" name="input" value="1" autofocus><i class="icons font-lg  mt-5 cui-arrow-right"></i><input id="i2" type="number" placeholder="0" name="input" value="1" autofocus></div><strong style="font-weight: bold;font-size: 17px;">=</strong>').appendTo(operador);
    return operador;
  },
  operador: function (id, nombre, css) {
    var operador = $('<span v-data="funcion" style="' + css + '"><strong style="padding-left: 4px;">' + nombre + '</strong></span>');
    $('<div id="values" v-type="' + id + '" ></div>').droppable({
      accept: "div,span",
      greedy: true,
      classes: {
        'ui-droppable-hover': 'hoverdropfunctions'
      },
      drop: function (event, ui) {

        if ($(ui.draggable).is('div') && $(ui.draggable).attr('v-tipo') == "1") {
          var variable = $('<span v-data="perfil">' + $(ui.draggable).text() + '</span>').css('border', '2px solid').draggable({
            revert: "invalid",
            containment: "document",
            helper: "clone",
            zIndex: 10000,
            start: function (event, ui) {
              $(this).draggable('instance').offset.click = {
                left: Math.floor(ui.helper.width() / 2),
                top: Math.floor(ui.helper.height() / 2)
              };
            }
          }).fadeOut("fast");
          ui.draggable.fadeOut("fast", function () {
            variable.appendTo(event.target).fadeIn();
            ui.draggable.fadeIn();
          });
        } else if ($(ui.draggable).is('span')) {
          ui.draggable.fadeOut(function () {
            $(ui.draggable).css('border', '2px solid').appendTo(event.target).fadeIn();
          });
        } else if ($(ui.draggable).is('div') && $(ui.draggable).attr('v-tipo') == "0") {

          var variable = $('<span v-data="datain" v-key="' + $(ui.draggable).attr('v-value') + '">' + $(ui.draggable).attr('v-view') + '</span>').fadeOut("fast");
          ui.draggable.fadeOut("fast", function () {
            variable.appendTo(event.target).fadeIn();
            ui.draggable.fadeIn();
          });

        } else if ($(ui.draggable).is('div') && $(ui.draggable).attr('v-tipo') == "6") {
          ui.draggable.fadeOut("fast", function () {
            var funcion = funciones.input(2, "Natural", "");
            ui.draggable.fadeIn();
            funcion.fadeOut("fast", function () {
              funcion.appendTo(event.target).fadeIn();
              $('>div', funcion).find('input').focus();
            });

          });
        } else {
          $(event.target).effect("pulsate", "slow");
          $(ui.draggable).effect("pulsate", "slow");
        }
      }
    }).appendTo(operador);
    return operador;
  },


  operadorG: function (id, nombre, css) {
    var operador = $('<span v-data="funcion"  style="' + css + '"><strong style="padding-left: 4px;">' + nombre + '</strong></span>');
    $('<div id="values" v-type="' + id + '"></div>').droppable({
      accept: "div,span",
      greedy: true,
      classes: {
        'ui-droppable-hover': 'hoverdropfunctions'
      },
      drop: function (event, ui) {

        if ($(ui.draggable).is('div') && $(ui.draggable).attr('v-tipo') == "1") {
          var variable = $('<span v-data="perfil" >' + $(ui.draggable).text() + '</span>').draggable({
            revert: "invalid",
            containment: "document",
            helper: "clone",
            zIndex: 10000,
            start: function (event, ui) {
              $(this).draggable('instance').offset.click = {
                left: Math.floor(ui.helper.width() / 2),
                top: Math.floor(ui.helper.height() / 2)
              };
            }
          }).fadeOut("fast");
          ui.draggable.fadeOut("fast", function () {
            variable.appendTo(event.target).fadeIn();
            ui.draggable.fadeIn();
          });
        } else if ($(ui.draggable).is('span')) {
          ui.draggable.fadeOut(function () {
            $(ui.draggable).appendTo(event.target).fadeIn();
          });
        } else {
          switch ($(ui.draggable).attr('v-tipo')) {
            case "0":
              var variable = $('<span v-data="datain" v-key="' + $(ui.draggable).attr('v-value') + '">' + $(ui.draggable).attr('v-view') + '</span>').fadeOut("fast");
              ui.draggable.fadeOut("fast", function () {
                variable.appendTo(event.target).fadeIn();
                ui.draggable.fadeIn();
              });
              break;
            case "2":
              var variable = $('<span v-data="operador" v-value="' + $(ui.draggable).attr('v-value') + '" ></span>').append($(ui.draggable).find('i').clone()).fadeOut("fast");
              ui.draggable.fadeOut("fast", function () {
                variable.appendTo(event.target).fadeIn();
                ui.draggable.fadeIn();
              });
              break;
            case "3":
              ui.draggable.fadeOut("fast", function () {
                var funcion = funciones.operador(3, "suma", "color: #bb5b20;");
                ui.draggable.fadeIn();
                funcion.fadeOut("fast", function () {
                  funcion.appendTo(event.target).fadeIn();
                });
              });
              break;
            case "4":
              ui.draggable.fadeOut("fast", function () {
                var funcion = funciones.operador(4, "resta", "color: #1d9009e0;");
                ui.draggable.fadeIn();
                funcion.fadeOut("fast", function () {
                  funcion.appendTo(event.target).fadeIn();
                });
              });
              break;
            case "5":
              ui.draggable.fadeOut("fast", function () {
                var funcion = funciones.operadorG(5, "grupo", "");
                ui.draggable.fadeIn();
                funcion.fadeOut("fast", function () {
                  funcion.appendTo(event.target).fadeIn();
                });
              });
              break;
            case "6":
              ui.draggable.fadeOut("fast", function () {

                var funcion = funciones.input(2, "Natural", "color: #20a8d8;");
                ui.draggable.fadeIn();
                funcion.fadeOut("fast", function () {
                  funcion.appendTo(event.target).fadeIn();
                  $('>div', funcion).find('input').focus();
                });
              });
              break;
            case "7":
                $(event.target).effect("pulsate", "slow");
                $(ui.draggable).effect("pulsate", "slow");  
            break;
          }
        }
      }
    }).appendTo(operador);
    return operador;
  }
};
export function generaperfil(mapinto, values = [], padre) {
  var idvista = generateKey();
  var idvalores = generateKey();
  var id_vista = '#' + idvista;
  var id_valores = '#' + idvalores;
  $('#' + padre).empty();
  $('<div class="col-md-3" style="padding: 16px !important;text-align: center;" id="' + idvalores + '" > </div>').appendTo($('#' + padre));
  $('<div class="col-md-9" style="padding: 0 !important;overflow-x: hidden; overflow-y: scroll; height: 100%;" id="' + idvista + '" ></div>').appendTo($('#' + padre));

  $(id_valores).css('border', '1px solid #40464a52');
  $(id_vista).empty();
  $(id_valores).empty();

  $(id_valores).append(' <u><h6 class="col-md-12" style="text-align: center;padding: 12px 0 12px 0;">Datos de Entrada</h6> </u>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="monto" v-data="perfil" v-view="Monto">Monto<span class="tooltiptext tooltip-bottom">Monto total.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="cuota" v-data="perfil" v-view="Cuota">Cuota<span class="tooltiptext tooltip-bottom">Cuota base perteneciente al periodo \'X\'.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="cuotaf" v-data="perfil" v-view="Cuota Final">Cuota Final<span class="tooltiptext tooltip-bottom">Cuota final perteneciente al periodo \'X\' contiene (interes, interes diferido, cargos adicionales).</span></div>');
  //$(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="prestamos" v-data="perfil" v-view="Prestamos">Prestamos<span class="tooltiptext tooltip-bottom">Es la suma de saldos de prestamos vigentes.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="capital" v-data="perfil" v-view="Capital">Capital<span class="tooltiptext tooltip-bottom">Es el monto que ira a amortizar a capital.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="intereses" v-data="perfil" v-view="Interes">Interes<span class="tooltiptext tooltip-bottom">Es la suma de los intereses generados.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="interes_diferido" v-data="perfil" v-view="Interes diferido">Interes diferido<span class="tooltiptext tooltip-bottom">Es la acumulación del interes del anterior prestamo.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="total_cuotas" v-data="perfil" v-view="Total cuotas">Total cuotas<span class="tooltiptext tooltip-bottom">Es el numero de cuotas del prestamo.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="total_refinanciar" v-data="perfil" v-view="Total saldo capital de prestamos vigentes">Total saldo capital de prestamos vigentes<span class="tooltiptext tooltip-bottom">Es la suma total del saldo capital de todos lo prestamos vigentes.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="segurodesgravamen" v-data="perfil" v-view="Total seguro desgravamen">Total seguro desgravamen<span class="tooltiptext tooltip-bottom">Es el calculo que se realiza al saldo capital del prestamo.</span></div>');
  $(id_valores).append(' <u><h6 class="col-md-12" style="text-align: center;padding: 12px 0 12px 0;">Cuentas del perfil</h6> </u>');

  values.forEach(function (element, index) {
    element.data = abc[index];
    element.data_php = '$'+abc[index];
    element.key = element.data + element.idperfilcuentadetalle;
    mapinto.set(index, element);
    $(id_vista).append((element.tipocargo == 'd' ? '<div id="' + index + '" class="row itemrow" style=" border: 1px solid grey;padding: 5px 0 5px 0;margin: 5px 0 5px 6px;background-color: #c0e0f5ba;">' +
        '<div class="col-md-1" style="margin: 0 0 0 -7px;display: inline-grid;align-content: center;"><span class="handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span></div><div class="col-md-11 row" style="margin: 0px;padding: 0px;"><h4 class="col-md-2">' + (element.tipocargo == 'd' ? 'Debe' : 'Haber') + '</h4><div class="col-md-10" style="text-align: left;font-weight: 700;font-size: 16px;">' + element.nomcuenta + '</div><div class="col-md-12" style="text-align: left;margin-bottom: 4px;"><div id="' + element.key + '" class="asig"><div  v-tipo="titulo">' + element.data + '</div><i class="fas fa-equals"></i></div><div id="' + element.key + '" class="divdrag" v-pos="left"></div>' :
        '<div id="' + index + '" class="row itemrow" style="border: 1px solid grey; padding: 5px 0 5px 0;margin: 5px 0 5px 6px;background-color: #e4e5e6;">' +
        '<div class="col-md-1" style="margin: 0 0 0 -7px;display: inline-grid;align-content: center;"><span class="handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span></div><div class="col-md-11 row" style="margin: 0px;padding: 0px;"><div class="col-md-10" style="text-align: right;font-weight: 700;font-size: 16px;">' + element.nomcuenta + '</div><h4 class="col-md-2">' + (element.tipocargo == 'd' ? 'Debe' : 'Haber') + '</h4><div class="col-md-12" style="text-align: right;margin-bottom: 4px;"><div id="' + element.key + '" class="asig"><div  v-tipo="titulo">' + element.data + '</div><i class="fas fa-equals"></i></div><div id="' + element.key + '" class="divdrag" v-pos="right"></div>') +
      '<button  v-clear="button" type="button"  class=" btn btn-danger btn-sm" ><i class="icon-trash"></i></button>' +
      '<div  v-type="checkconten" class="col-md-4" style="padding: 2px 0 2px 11px;border-radius: 20px;height: 28px;margin-top: 5px;display: inline-flex;    border: 1px solid rgb(194, 207, 214);"> <label style="padding-right: 9px;font-weight: 600;">Aplica a cargo adicional :</label> <label class="switch switch-sm switch-label switch-pill switch-primary"> <input v-type="check" class="switch-input" type="checkbox"> <span class="switch-slider" data-checked="Si" data-unchecked="No"></span> </label></div>' +
      '<div  v-type="checkcontencomision" class="col-md-4" style="padding: 2px 0 2px 11px;border-radius: 20px;height: 28px;margin-top: 5px;display: inline-flex;    border: 1px solid rgb(194, 207, 214);"> <label style="padding-right: 9px;font-weight: 600;">Aplica a comisión :</label> <label class="switch switch-sm switch-label switch-pill switch-primary"> <input v-type="check" class="switch-input" type="checkbox"> <span class="switch-slider" data-checked="Si" data-unchecked="No"></span> </label></div>' +
      '</div></div>');
    $(id_valores).append('<div v-tipo="1" v-data="perfil">' + element.data + '</div>');
  });


  $(id_valores).append(' <u><h6 class="col-md-12" style="text-align: center;padding: 12px 0 12px 0;">Operadores</h6> </u>');
  $(id_valores).append('<div v-tipo="2" v-data="perfil" v-value="+"><i class="fa fa-plus"></i></div>');
  $(id_valores).append('<div v-tipo="2" v-data="perfil" v-value="-"><i class="fa fa-minus"></i></div>');
  $(id_valores).append('<div v-tipo="2" v-data="perfil" v-value="*"><i class="fa fa-times"></i></div>');
  $(id_valores).append('<div v-tipo="2" v-data="perfil" v-value="/"><i class="fa fa-divide"></i></div>');

  $(id_valores).append(' <u><h6 class="col-md-12" style="text-align: center;padding: 12px 0 12px 0;">Funciones</h6> </u>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="3" style="color:white;background: #bb5b20;padding: 3px;" v-data="perfil">SUMA&nbsp;(&nbsp;)<span class="tooltiptext tooltip-bottom">Obtiene la suma total de todos los componentes ingresados.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="4" style="color:white;background: #1d9009e0;padding: 3px;" v-data="perfil">RESTA&nbsp;(&nbsp;)<span class="tooltiptext tooltip-bottom">Obtiene la resta total de todos los componentes ingresados (el resultado siempre sera positivo).</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="5" style="padding: 3px;" v-data="perfil">GRUPO&nbsp;(&nbsp;)<span class="tooltiptext tooltip-bottom">Permite la agrupacion de componentes para facilitar la operacion matematica.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="6" style="color:white;background: #20a8d8;padding: 3px;" v-data="perfil">INPUT&nbsp;(int)<span class="tooltiptext tooltip-bottom">Permite la insercion de valores numericos.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="7" style="color:#151b1e;background: rgb(197, 149, 5);padding: 3px;" v-data="perfil">SI&nbsp;(int->int) = proceso<span class="tooltiptext tooltip-bottom">Valida el resultado de un proceso en un rango admitido, valor por defecto 0.</span></div>');

  $("div,label", id_valores).draggable({
    revert: "invalid",
    containment: "document",
    helper: "clone",
    zIndex: 10000,
    start: function (event, ui) {
      $(ui.helper).find('.tooltiptext').remove();
      $(this).draggable('instance').offset.click = {
        left: Math.floor(ui.helper.width() / 2),
        top: Math.floor(ui.helper.height() / 2)
      };
    }
  });


  Sortable.create(document.getElementById(idvista), {
    handle: '.handle',
    animation: 150,
    onEnd: function (evt) {
      var paso = true;
      while (paso) {
        paso = false;
        for (var [key, value] of mapinto) {
          var item = $('.itemrow[id="' + key + '"]').index();
          if (item != key) {
            var valuitemkey = mapinto.get(key);
            if ($('.itemrow[id="' + key + '"] .divdrag[id="' + valuitemkey.key + '"]').find('[v-data="perfil"]').length > 0) {
              $('.itemrow[id="' + key + '"] .divdrag[id="' + valuitemkey.key + '"]').empty();
            }
            $('.itemrow[id="' + key + '"] .divdrag[id="' + valuitemkey.key + '"]').attr('id', abc[item] + valuitemkey.idperfilcuentadetalle);
            $('.itemrow[id="' + key + '"] .asig[id="' + valuitemkey.key + '"] div[v-tipo="titulo"]').text(abc[item]);
            $('.itemrow[id="' + key + '"] .asig[id="' + valuitemkey.key + '"]').attr('id', abc[item] + valuitemkey.idperfilcuentadetalle);
            valuitemkey.data = abc[item];
            valuitemkey.key = valuitemkey.data + valuitemkey.idperfilcuentadetalle;
            var valuitem = mapinto.get(item);
            if ($('.itemrow[id="' + item + '"] .divdrag[id="' + valuitem.key + '"]').find('[v-data="perfil"]').length > 0) {
              $('.itemrow[id="' + item + '"] .divdrag[id="' + valuitem.key + '"]').empty();
            }
            $('.itemrow[id="' + item + '"] .divdrag[id="' + valuitem.key + '"]').attr('id', abc[key] + valuitem.idperfilcuentadetalle);
            $('.itemrow[id="' + item + '"] .asig[id="' + valuitem.key + '"] div[v-tipo="titulo"]').text(abc[key]);
            $('.itemrow[id="' + item + '"] .asig[id="' + valuitem.key + '"]').attr('id', abc[key] + valuitem.idperfilcuentadetalle);
            valuitem.data = abc[key];
            valuitem.key = valuitem.data + valuitem.idperfilcuentadetalle;
            mapinto.set(key, valuitem);
            mapinto.set(item, valuitemkey);
            var mainkey = $('.itemrow[id="' + key + '"]')
            var mainitem = $('.itemrow[id="' + item + '"]')
            mainkey.attr('id', item);
            mainitem.attr('id', key);
            paso = true;
            break;
          }
        }
      }
    }
  });

  $('button[v-clear="button"]').click(function () {
    $(this).siblings('.divdrag').empty();
  });
  isdroppable(id_vista);
}
export function _mff82sdf_mjd(mapinto, padre) {
  var idvista = generateKey();
  var idvalores = generateKey();
  var id_vista = '#' + idvista;
  var id_valores = '#' + idvalores;
  $('#' + padre).empty();
  $('<div class="col-md-3" style="padding: 16px !important;text-align: center;" id="' + idvalores + '" > </div>').appendTo($('#' + padre));
  $('<div class="col-md-9" style="padding: 0 !important;overflow-x: hidden; overflow-y: scroll; height: 555px;" id="' + idvista + '" ></div>').appendTo($('#' + padre));

  $(id_valores).css('border', '1px solid #40464a52');
  $(id_vista).empty();
  $(id_valores).empty();

  $(id_valores).append(' <u><h6 class="col-md-12" style="text-align: center;padding: 12px 0 12px 0;">Datos de Entrada</h6> </u>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="monto" v-data="perfil" v-view="Monto">Monto<span class="tooltiptext tooltip-bottom">Monto total.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="cuota" v-data="perfil" v-view="Cuota">Cuota<span class="tooltiptext tooltip-bottom">Cuota perteneciente al periodo \'X\'.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="cuotaf" v-data="perfil" v-view="Cuota Final">Cuota Final<span class="tooltiptext tooltip-bottom">Cuota final perteneciente al periodo \'X\' contiene (interes, interes diferido, cargos adicionales).</span></div>');
  //$(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="prestamos" v-data="perfil" v-view="Prestamos">Prestamos<span class="tooltiptext tooltip-bottom">Es la suma de saldos de prestamos vigentes.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="capital" v-data="perfil" v-view="Capital">Capital<span class="tooltiptext tooltip-bottom">Es el monto que ira a amortizar a capital.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="intereses" v-data="perfil" v-view="Interes">Interes<span class="tooltiptext tooltip-bottom">Es la suma de los intereses generados.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="interes_diferido" v-data="perfil" v-view="Interes diferido">Interes diferido<span class="tooltiptext tooltip-bottom">Es la acumulación del interes del anterior prestamo.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="total_cuotas" v-data="perfil" v-view="Total cuotas">Total cuotas<span class="tooltiptext tooltip-bottom">Es el numero de cuotas del prestamo.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="total_refinanciar" v-data="perfil" v-view="Total saldo capital de prestamos vigentes">Total saldo capital de prestamos vigentes<span class="tooltiptext tooltip-bottom">Es la suma total del saldo capital de todos lo prestamos vigentes.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="0" v-value="segurodesgravamen" v-data="perfil" v-view="Total seguro desgravamen">Total seguro desgravamen<span class="tooltiptext tooltip-bottom">Es el calculo que se realiza al saldo capital del prestamo.</span></div>');
  $(id_valores).append(' <u><h6 class="col-md-12" style="text-align: center;padding: 12px 0 12px 0;">Cuentas del perfil</h6> </u>');


  for (var [index, element] of mapinto) {
    $(id_vista).append(element.item);
    $(id_valores).append('<div v-tipo="1" v-data="perfil">' + element.data + '</div>');

    var item = $('.itemrow[id="' + index + '"] .divdrag[id="' + element.key + '"]');
    if (element.adi) {
      item.siblings('div[v-type="checkconten"]').find('input[v-type="check"]').attr('checked', true);
    } else {
      item.siblings('div[v-type="checkconten"]').find('input[v-type="check"]').attr('checked', false);
    }
    if (element.comision) {
      item.siblings('div[v-type="checkcontencomision"]').find('input[v-type="check"]').attr('checked', true);
    } else {
      item.siblings('div[v-type="checkcontencomision"]').find('input[v-type="check"]').attr('checked', false);
    }

  }



  $(id_valores).append(' <u><h6 class="col-md-12" style="text-align: center;padding: 12px 0 12px 0;">Operadores</h6> </u>');
  $(id_valores).append('<div v-tipo="2" v-data="perfil" v-value="+"><i class="fa fa-plus"></i></div>');
  $(id_valores).append('<div v-tipo="2" v-data="perfil" v-value="-"><i class="fa fa-minus"></i></div>');
  $(id_valores).append('<div v-tipo="2" v-data="perfil" v-value="*"><i class="fa fa-times"></i></div>');
  $(id_valores).append('<div v-tipo="2" v-data="perfil" v-value="/"><i class="fa fa-divide"></i></div>');

  $(id_valores).append(' <u><h6 class="col-md-12" style="text-align: center;padding: 12px 0 12px 0;">Funciones</h6> </u>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="3" style="color:white;background: #bb5b20;padding: 3px;" v-data="perfil">SUMA&nbsp;(&nbsp;)<span class="tooltiptext tooltip-bottom">Obtiene la suma total de todos los componentes ingresados.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="4" style="color:white;background: #1d9009e0;padding: 3px;" v-data="perfil">RESTA&nbsp;(&nbsp;)<span class="tooltiptext tooltip-bottom">Obtiene la resta total de todos los componentes ingresados (el resultado siempre sera positivo).</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="5" style="padding: 3px;" v-data="perfil">GRUPO&nbsp;(&nbsp;)<span class="tooltiptext tooltip-bottom">Permite la agrupacion de componentes para facilitar la operacion matematica.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="6" style="color:white;background: #20a8d8;padding: 3px;" v-data="perfil">INPUT&nbsp;(int)<span class="tooltiptext tooltip-bottom">Permite la insercion de valores numericos.</span></div>');
  $(id_valores).append('<div class="tooltipdiv" v-tipo="7" style="color:#151b1e;background: rgb(197, 149, 5);padding: 3px;" v-data="perfil">SI&nbsp;(int->int) = proceso<span class="tooltiptext tooltip-bottom">Valida el resultado de un proceso en un rango admitido, valor por defecto 0.</span></div>');

  $("div,label", id_valores).draggable({
    revert: "invalid",
    containment: "document",
    helper: "clone",
    zIndex: 10000,
    start: function (event, ui) {
      $(ui.helper).find('.tooltiptext').remove();
      $(this).draggable('instance').offset.click = {
        left: Math.floor(ui.helper.width() / 2),
        top: Math.floor(ui.helper.height() / 2)
      };
    }
  });


  Sortable.create(document.getElementById(idvista), {
    handle: '.handle',
    animation: 150,
    onEnd: function (evt) {
      var paso = true;
      while (paso) {
        paso = false;
        for (var [key, value] of mapinto) {
          var item = $('.itemrow[id="' + key + '"]').index();
          if (item != key) {
            var valuitemkey = mapinto.get(key);
            if ($('.itemrow[id="' + key + '"] .divdrag[id="' + valuitemkey.key + '"]').find('[v-data="perfil"]').length > 0) {
              $('.itemrow[id="' + key + '"] .divdrag[id="' + valuitemkey.key + '"]').empty();
            }
            $('.itemrow[id="' + key + '"] .divdrag[id="' + valuitemkey.key + '"]').attr('id', abc[item] + valuitemkey.idperfilcuentadetalle);
            $('.itemrow[id="' + key + '"] .asig[id="' + valuitemkey.key + '"] div[v-tipo="titulo"]').text(abc[item]);
            $('.itemrow[id="' + key + '"] .asig[id="' + valuitemkey.key + '"]').attr('id', abc[item] + valuitemkey.idperfilcuentadetalle);
            valuitemkey.data = abc[item];
            valuitemkey.key = valuitemkey.data + valuitemkey.idperfilcuentadetalle;
            var valuitem = mapinto.get(item);
            if ($('.itemrow[id="' + item + '"] .divdrag[id="' + valuitem.key + '"]').find('[v-data="perfil"]').length > 0) {
              $('.itemrow[id="' + item + '"] .divdrag[id="' + valuitem.key + '"]').empty();
            }
            $('.itemrow[id="' + item + '"] .divdrag[id="' + valuitem.key + '"]').attr('id', abc[key] + valuitem.idperfilcuentadetalle);
            $('.itemrow[id="' + item + '"] .asig[id="' + valuitem.key + '"] div[v-tipo="titulo"]').text(abc[key]);
            $('.itemrow[id="' + item + '"] .asig[id="' + valuitem.key + '"]').attr('id', abc[key] + valuitem.idperfilcuentadetalle);
            valuitem.data = abc[key];
            valuitem.key = valuitem.data + valuitem.idperfilcuentadetalle;
            mapinto.set(key, valuitem);
            mapinto.set(item, valuitemkey);
            var mainkey = $('.itemrow[id="' + key + '"]')
            var mainitem = $('.itemrow[id="' + item + '"]')
            mainkey.attr('id', item);
            mainitem.attr('id', key);
            paso = true;
            break;
          }
        }
      }
    }
  });

  $('button[v-clear="button"]').click(function () {
    $(this).siblings('.divdrag').empty();
  });
  isdroppable(id_vista);
}

function isdroppable(id) {
  $('.divdrag', id).droppable({
    accept: "div,span,label",
    greedy: true,
    classes: {
      'ui-droppable-hover': 'hoverdrop'
    },
    drop: function (event, ui) {
     
      $(event.target).removeClass('noF');
      if ($(ui.draggable).is('div')) {
        switch ($(ui.draggable).attr('v-tipo')) {
          case "0":
            var variable = $('<span v-data="datain" v-key="' + $(ui.draggable).attr('v-value') + '">' + $(ui.draggable).attr('v-view') + '</span>').fadeOut("fast");
            ui.draggable.fadeOut("fast", function () {
              variable.appendTo(event.target).fadeIn();
              ui.draggable.fadeIn();
            });
            break;
          case "1":
            var variable = $('<span v-data="perfil">' + $(ui.draggable).text() + '</span>').draggable({
              revert: "invalid",
              containment: "document",
              helper: "clone",
              zIndex: 10000,
              start: function (event, ui) {
                $(this).draggable('instance').offset.click = {
                  left: Math.floor(ui.helper.width() / 2),
                  top: Math.floor(ui.helper.height() / 2)
                };
              }
            }).fadeOut("fast");
            ui.draggable.fadeOut("fast", function () {
              variable.appendTo(event.target).fadeIn();
              ui.draggable.fadeIn();
            });
            break;
          case "2":
            var variable = $('<span v-data="operador" v-value="' + $(ui.draggable).attr('v-value') + '" ></span>').append($(ui.draggable).find('i').clone()).fadeOut("fast");
            ui.draggable.fadeOut("fast", function () {
              variable.appendTo(event.target).fadeIn();
              ui.draggable.fadeIn();
            });
            break;
          case "3":
            ui.draggable.fadeOut("fast", function () {
              var funcion = funciones.operador(3, "suma", "color: #bb5b20;");
              ui.draggable.fadeIn();
              funcion.fadeOut("fast", function () {
                funcion.appendTo(event.target).fadeIn();
              });
            });
            break;
          case "4":
            ui.draggable.fadeOut("fast", function () {
              var funcion = funciones.operador(4, "resta", "color: #1d9009e0;");
              ui.draggable.fadeIn();
              funcion.fadeOut("fast", function () {
                funcion.appendTo(event.target).fadeIn();
              });
            });
            break;
          case "5":
            ui.draggable.fadeOut("fast", function () {
              var funcion = funciones.operadorG(5, "grupo", "");
              ui.draggable.fadeIn();
              funcion.fadeOut("fast", function () {
                funcion.appendTo(event.target).fadeIn();
              });
            });
            break;
          case "6":
            ui.draggable.fadeOut("fast", function () {

              var funcion = funciones.input(2, "Natural", "color: #20a8d8;");
              ui.draggable.fadeIn();
              funcion.fadeOut("fast", function () {
                funcion.appendTo(event.target).fadeIn();
                $('>div', funcion).find('input').focus();
              });
            });
            break;
          case "7":
            if(event.target.childElementCount==0){
              ui.draggable.fadeOut("fast", function () { 
                var funcion = funciones.inputSi(7, "Si", "color:rgb(197, 149, 5);");
                ui.draggable.fadeIn();
                funcion.fadeOut("fast", function () {
                  funcion.appendTo(event.target).fadeIn(); 
                });
              });
            }else{
              $(event.target).effect("pulsate", "slow");
              $(ui.draggable).effect("pulsate", "slow");  
            }
              

              break;
        }
      } else {
        ui.draggable.fadeOut(function () {
          $(ui.draggable).css('border', '1px solid').appendTo(event.target).fadeIn();
        });
      }
    }
  });
}
export function _perff_00125(buscar) {
  var pasoSi=false;
   var formula = '';
   $(buscar).children("span").each(function (i, elm) {
     switch ($(elm).attr('v-data')) {
       case 'datain':
         formula += $(elm).attr('v-key');
         break;
       case 'perfil':
         formula += $(elm).text();
         break;
       case 'operador':
         formula += $(elm).attr('v-value');
         break;
       case 'funcion':
         var item = $('>div', elm);
         switch (item.attr('v-type')) {
           case '3':
             var salida = '';
             $(item).children("span").each(function (i, element) {
               switch ($(element).attr('v-data')) {
                 case 'perfil':
                   if (salida.length == 0) {
                     salida += $(element).text();
                   } else {
                     salida += '+' + $(element).text();
                   }
                   break;
                 case 'input':
                   var valor = $(element).find('input').val();
                   $(element).find('input').attr('value', valor);
                   switch ($(element).attr('v-type')) {
                     case '1':
                       if (salida.length == 0) {
                         salida += 'operador(' + valor + '*tipodecambio)';
                       } else {
                         salida += '+operador(' + valor + '*tipodecambio)';
                       }
                       break;
                     default:
                       if (salida.length == 0) {
                         salida += valor;
                       } else {
                         salida += valor;
                       }
                       break;
                   }
                   break;
                 case 'datain':
                   if (salida.length == 0) {
                     salida += $(element).attr('v-key');
                   } else {
                     salida += '+' + $(element).attr('v-key');
                   }
                   break;
               }
             });
             formula += 'operador("' + salida + '")';
             break;
           case '4':
             var salida = '';
             $(item).children("span").each(function (i, element) {
               switch ($(element).attr('v-data')) {
                 case 'perfil':
                   if (salida.length == 0) {
                     salida += $(element).text();
                   } else {
                     salida += '-' + $(element).text();
                   }
                   break;
                 case 'input':
                   var valor = $(element).find('input').val();
                   $(element).find('input').attr('value', valor);
                   switch ($(element).attr('v-type')) {
                     case '1':
                       if (salida.length == 0) {
                         salida += 'operador(' + valor + '*tipodecambio)';
                       } else {
                         salida += '+operador(' + valor + '*tipodecambio)';
                       }
                       break;
                     default:
                       if (salida.length == 0) {
                         salida += valor;
                       } else {
                         salida += valor;
                       }
                       break;
                   }
                   break;
                 case 'datain':
                   if (salida.length == 0) {
                     salida += $(element).attr('v-key');
                   } else {
                     salida += '+' + $(element).attr('v-key');
                   }
                   break;
               }
             });
             formula += 'operador("' + salida + '")';
             break;
           case '5':
             formula += 'operador(' + _perff_00125(item) + ')';
             break;
         }
         break;
       case 'input':
         var valor = $(elm).find('input').val();
         $(elm).find('input').attr('value', valor);
         switch ($(elm).attr('v-type')) {
           case '1':
             formula += 'operador(' + valor + '*tipodecambio)';
             break;
           default:
             formula += valor;
             break;
         }
         break;
       case 'inputSi':
         var valor1 = $(elm).find('input[id="i1"]').val();
         var valor2 = $(elm).find('input[id="i2"]').val();
         $(elm).find('input[id="i1"]').attr('value', valor1);
         $(elm).find('input[id="i2"]').attr('value', valor2);
         formula += 'si(' + valor1 + ' , ' + valor2 + ',';
         pasoSi=true;
         break;
 
         
     }
   });
 
 
   return pasoSi?formula+=')':formula;
 }
export function _perff_00126(buscar) {
 var pasoSi=false;
  var formula = '';
  $(buscar).children("span").each(function (i, elm) {
    switch ($(elm).attr('v-data')) {
      case 'datain':
        formula += '$'+$(elm).attr('v-key');
        break;
      case 'perfil':
        formula += '$'+$(elm).text();
        break;
      case 'operador':
        formula += $(elm).attr('v-value');
        break;
      case 'funcion':
        var item = $('>div', elm);
        switch (item.attr('v-type')) {
          case '3':
            var salida = '';
            $(item).children("span").each(function (i, element) {
              switch ($(element).attr('v-data')) {
                case 'perfil':
                  if (salida.length == 0) {
                    salida +='$'+ $(element).text();
                  } else {
                    salida += '+$' + $(element).text();
                  }
                  break;
                case 'input':
                  var valor = $(element).find('input').val();
                  $(element).find('input').attr('value', valor);
                  switch ($(element).attr('v-type')) {
                    case '1':
                      if (salida.length == 0) {
                        salida += '(' + valor + '*$tipodecambio)';
                      } else {
                        salida += '+(' + valor + '*$tipodecambio)';
                      }
                      break;
                    default:
                      if (salida.length == 0) {
                        salida += valor;
                      } else {
                        salida += valor;
                      }
                      break;
                  }
                  break;
                case 'datain':
                  if (salida.length == 0) {
                    salida += '$'+$(element).attr('v-key');
                  } else {
                    salida += '+$' + $(element).attr('v-key');
                  }
                  break;
              }
            });
            formula += '(' + salida + ')';
            break;
          case '4':
            var salida = '';
            $(item).children("span").each(function (i, element) {
              switch ($(element).attr('v-data')) {
                case 'perfil':
                  if (salida.length == 0) {
                    salida += '$'+$(element).text();
                  } else {
                    salida += '-$' + $(element).text();
                  }
                  break;
                case 'input':
                  var valor = $(element).find('input').val();
                  $(element).find('input').attr('value', valor);
                  switch ($(element).attr('v-type')) {
                    case '1':
                      if (salida.length == 0) {
                        salida += '(' + valor + '*$tipodecambio)';
                      } else {
                        salida += '+(' + valor + '*$tipodecambio)';
                      }
                      break;
                    default:
                      if (salida.length == 0) {
                        salida += valor;
                      } else {
                        salida += valor;
                      }
                      break;
                  }
                  break;
                case 'datain':
                  if (salida.length == 0) {
                    salida += '$'+$(element).attr('v-key');
                  } else {
                    salida += '+$' + $(element).attr('v-key');
                  }
                  break;
              }
            });
            formula += '(' + salida + ')';
            break;
          case '5':
            formula += '(' + _perff_00126(item) + ')';
            break;
        }
        break;
      case 'input':
        var valor = $(elm).find('input').val();
        $(elm).find('input').attr('value', valor);
        switch ($(elm).attr('v-type')) {
          case '1':
            formula += '(' + valor + '*$tipodecambio)';
            break;
          default:
            formula += valor;
            break;
        }
        break;
      case 'inputSi':
        var valor1 = $(elm).find('input[id="i1"]').val();
        var valor2 = $(elm).find('input[id="i2"]').val();
        $(elm).find('input[id="i1"]').attr('value', valor1);
        $(elm).find('input[id="i2"]').attr('value', valor2);
        formula += '$this->si(' + valor1 + ' , ' + valor2 + ',';
        pasoSi=true;
        break;

        
    }
  });


  return pasoSi?formula+=')':formula;
}

export function _mmf2251_3325(mapinto) {
  closeRam();
  window.monto = 15000;
  window.cuota = 196.44;
  window.cuotaf = 197.13;
  window.prestamos = 0;
  window.capital = 52.44;
  window.intereses = 144;
  window.interes_diferido = 144;
  window.total_cuotas = 120;
  window.total_refinanciar = 1000;
  window.segurodesgravamen = 0;
  window.tipodecambio = 1;
  mapinto.forEach(function (element, key) {

    try {
      jQuery.globalEval(element.data + '=' + redondeo_valor(eval(element.formula)) + ';');
    } catch (error) {
      var e = new Error(error.message);
      e.name = '<div  v-tipo="titulo"><span style="font-weight: 400;">' + error.name + ' in </span>' + element.data + '</div> ';
      e.stack = error.stack;
      throw e;
    }

  });

}

function closeRam() {
  abc.forEach(function (element) {
    try {
      if (eval(element)) {
        jQuery.globalEval('delete ' + element + ';');
      }
    } catch (error) {}

  });
}
export function _mf362353_225(mapinto, padre, tipoc, cod, namep) {
  closeRam();
  window.monto = 15000;
  window.cuota = 196.44;
  window.cuotaf = 197.13;
  window.prestamos = 0;
  window.capital = 52.44;
  window.intereses = 144;
  window.interes_diferido = 0;
  window.total_cuotas = 120;
  window.total_refinanciar = 1000;
  window.segurodesgravamen = 0;
  window.tipodecambio = tipoc;


  var idvista = generateKey();
  var pie = generateKey();
  var idvalores = generateKey();
  var id_vista = '#' + idvista;
  var id_pie = '#' + pie;
  var id_valores = '#' + idvalores;

  $('#' + padre).empty();
  $('<div class="col-md-12" style="padding: 10px !important;text-align: center;font-size: 18px;margin: 6px;font-weight: bold;text-decoration-line: underline;" >PRODUCTO : ' + namep + '</div>').appendTo($('#' + padre));
  $('<div class="col-md-3" style="padding: 16px !important;text-align: center;" id="' + idvalores + '" > </div>').appendTo($('#' + padre));
  $('<div class="col-md-9" style="padding: 0 !important;overflow-x: hidden; overflow-y: scroll; height: 444px;" id="' + idvista + '" ></div>').appendTo($('#' + padre));
  $('<div class="col-md-12" style="margin-top: 5px;padding: 10px !important;text-align: right;background-color: #25d82029;border: 1px solid rgba(64, 70, 74, 0.32);" id="' + pie + '" ></div>').appendTo($('#' + padre));

  $(id_valores).css('border', '1px solid #40464a52');
  $(id_vista).empty();
  $(id_valores).empty();

  $(id_valores).append(' <u><h6 class="col-md-12" style="text-align: center;padding: 12px 0 12px 0;">Datos del prestamo</h6> </u>');
  $(id_valores).append('<div class="col-md-12" style="text-align: left !important;" v-data="perfil" >Monto&nbsp;=&nbsp;<span style="font-weight: 600;font-size: 15px;color: #007bff;">' + monto + '&nbsp;' + cod + '</span></div>');
  $(id_valores).append('<div class="col-md-12" style="text-align: left !important;" v-data="perfil" >Cuota&nbsp;=&nbsp;<span style="font-weight: 600;font-size: 15px;color: #007bff;">' + cuota + '&nbsp;' + cod + '</span></div>');
  $(id_valores).append('<div class="col-md-12" style="text-align: left !important;" v-data="perfil" >Cuota final&nbsp;=&nbsp;<span style="font-weight: 600;font-size: 15px;color: #007bff;">' + cuotaf + '&nbsp;' + cod + '</span></div>');
  //$(id_valores).append('<div class="col-md-12" style="text-align: left !important;" v-data="perfil" >Prestamos&nbsp;=&nbsp;<span style="font-weight: 600;font-size: 15px;color: #007bff;">'+prestamos+'&nbsp;'+cod+'</span></div>');
  $(id_valores).append('<div class="col-md-12" style="text-align: left !important;" v-data="perfil" >Capital&nbsp;=&nbsp;<span style="font-weight: 600;font-size: 15px;color: #007bff;">' + capital + '&nbsp;' + cod + '</span></div>');
  $(id_valores).append('<div class="col-md-12" style="text-align: left !important;" v-data="perfil" >Interes&nbsp;=&nbsp;<span style="font-weight: 600;font-size: 15px;color: #007bff;">' + intereses + '&nbsp;' + cod + '</span></div>');
  $(id_valores).append('<div class="col-md-12" style="text-align: left !important;" v-data="perfil" >Interes diferido&nbsp;=&nbsp;<span style="font-weight: 600;font-size: 15px;color: #007bff;">' + interes_diferido + '&nbsp;' + cod + '</span></div>');
  $(id_valores).append('<div class="col-md-12" style="text-align: left !important;" v-data="perfil" >Total cuotas&nbsp;=&nbsp;<span style="font-weight: 600;font-size: 18px;color: #007bff;">' + total_cuotas + '</span></div>');
  $(id_valores).append('<div class="col-md-12" style="text-align: left !important;" v-data="perfil" >Total saldo capital&nbsp;=&nbsp;<span style="font-weight: 600;font-size: 18px;color: #007bff;">' + total_refinanciar + '&nbsp;BOB</span></div>');

  monto = redondeo_valor(monto * tipodecambio);
  cuota = redondeo_valor(cuota * tipodecambio);
  cuotaf = redondeo_valor(cuotaf * tipodecambio); 
  capital = redondeo_valor(capital * tipodecambio);
  intereses = redondeo_valor(intereses * tipodecambio);
  interes_diferido = redondeo_valor(interes_diferido * tipodecambio);

  mapinto.forEach(function (element, key) {
    // console.log('formula: ' + element.formula);
    try {
      jQuery.globalEval(element.data + '=' + redondeo_valor(eval(element.formula)) + ';');
    } catch (error) {
      var e = new Error(error.message);
      e.name = '<div  v-tipo="titulo"><span style="font-weight: 400;">' + error.name + ' in </span>' + element.data + '</div> ';
      e.stack = error.stack;
      throw e;
    }

  }); 

  var countDebe = 0;
  var countHaber = 0;
  var totalcodmoneda = '';
  mapinto.forEach(function (element, key) {
    var iddrop = '#' + element.key + '.view';
    $(id_vista).append((element.tipocargo == 'd' ? '<div class="row" style=" padding: 5px 0 5px 0;margin: 5px 0 5px 6px;background-color: #c0e0f5ba;"><h4 class="col-md-2">' + (element.tipocargo == 'd' ? 'Debe' : 'Haber') + '</h4><div class="col-md-10">' + element.nomcuenta + '</div><div class="col-md-12" style="text-align: left;margin-bottom: 4px;"><div id="' + element.key + '" class="asig"><div  v-tipo="titulo">' + element.data + '</div><i class="fas fa-equals"></i></div><div id="' + element.key + '" class="divdrag view" v-pos="left"></div>' :
        '<div class="row" style=" padding: 5px 0 5px 0;margin: 5px 0 5px 6px;background-color: #e4e5e6;"><div class="col-md-10" style="text-align: right;">' + element.nomcuenta + '</div><h4 class="col-md-2">' + (element.tipocargo == 'd' ? 'Debe' : 'Haber') + '</h4><div class="col-md-12" style="text-align: right;margin-bottom: 4px;"><div id="' + element.key + '" class="asig"><div  v-tipo="titulo">' + element.data + '</div><i class="fas fa-equals"></i></div><div id="' + element.key + '" class="divdrag view" v-pos="right"></div>') +
      '</div></div>');
    try {
      var valuecuenta = (eval(element.data) / element.tipocambio);
      $(iddrop).append('<b>' + valuecuenta + '</b>&nbsp;<b>' + element.codmoneda + '</b>');
      totalcodmoneda = element.codmoneda;
      if (element.tipocargo == 'd') {
        countDebe += valuecuenta;
      } else {
        countHaber += valuecuenta;
      }

    } catch (error) {
      var e = new Error(error.message);
      e.name = '<div  v-tipo="titulo"><span style="font-weight: 400;">' + error.name + ' when assigning </span>' + element.data + '</div> ';
      e.stack = error.stack;
      throw e;
    }

  });
  $(id_pie).append('<div class="col-md-3" style="display: inline-block;text-align: left;"><strong style="font-size: 18px;">Totales</strong> </div>' +
    '<div class="col-md-4" style="display: inline-block;text-align: left;margin-right: 60px;"><strong style="font-size: 18px;">Debe : </strong> ' + countDebe + '&nbsp;' + totalcodmoneda +
    '</div><div class="col-md-4" style="display: inline-block;"><strong style="font-size: 18px;">Haber : </strong>' + countHaber + '&nbsp;' + totalcodmoneda + '</div>');


}

function operador(into) {
  var value = into + '';
  if (value.trim().length == 0) {
    throw new Error('The function does not contain data.');
  }
  return redondeo_valor(eval(value));
}
function si(v,b,e) {  
  var outnum=0;
if(typeof e === 'undefined'){
  throw new Error("Exist an error in the function syntax.(The function it's not complete)");
}
if(b>v){ 
  outnum = v<=e&&e<=b?e:0;  
}else{ 
  throw new Error('Exist an error in the function syntax.('+b+' its not higher to '+v+')');
} 

  return redondeo_valor(outnum<0?(outnum*-1):outnum);
}



function valueformulacargos(arrayformulas, tcuo, tipoc, mon, cuo, prs, cap, int, intdif) {
  closeRam();
  window.total_cuotas = tcuo;
  window.tipodecambio = tipoc;
  window.monto = (mon);
  window.cuota = (cuo);
  window.cuotaf = (cuo);
  window.prestamos = (prs);
  window.total_refinanciar = (prs);
  window.segurodesgravamen = 0;
  window.capital = (cap);
  window.intereses = (int);
  window.interes_diferido = (intdif);
  var cargos = [];
  for (var index in arrayformulas) {
    try {
      var element = arrayformulas[index];
      jQuery.globalEval(element.valor_abc + '=' + redondeo_valor(eval(element.formula)) + ';');

      if (element.iscargo) {
        var out = eval(element.valor_abc); 
        if (parseFloat(out) > 0) {
          cargos.push({
            id: element.idperfilcuentadetalle,
            value: out,
            rev:element.formula.includes("total_cuotas")?1:0
          });
        }
      }
    } catch (error) {
      var e = new Error(error.message);
      e.name = '<div  v-tipo="titulo"><span style="font-weight: 400;">' + error.name + ' in </span>' + element.valor_abc + '</div> ';
      e.stack = error.stack;
      throw e;
    }
  }
  return cargos;
}

function valueformulaDesembolso(arrayformulas, tcuo, tipoc, mon, cuo, prs, numpa) {
  closeRam();
  window.total_cuotas = tcuo;
  window.total_refinanciar = prs;
  window.segurodesgravamen = 0;
  window.tipodecambio = tipoc;
  window.monto = (mon);
  window.cuota = (cuo);
  window.cuotaf = (cuo);
  window.prestamos = prs;
  // console.log('desembolso');
  // console.log(arrayformulas);
  var cargos = [];
  for (var index in arrayformulas) {
    try {
      var element = arrayformulas[index];
      jQuery.globalEval(element.valor_abc + '=' + redondeo_valor(eval(element.formula)) + ';');

      var out = parseFloat(eval(element.valor_abc));
      // console.log(element.valor_abc + '=' + out);


      if (parseFloat(out) > 0) {
        if (parseFloat(tipoc) > 1) {
          out = parseFloat(out) * parseFloat(tipoc);
        }
        out = parseFloat(redondeo_valor(out));
        if (element.tipocargo === 'h') {
          out *= -1;
        }
        cargos.push({
          num: numpa,
          idmaestro: element.desembolso_perfil,
          iddetalle: element.idperfilcuentadetalle,
          value: out,
          id: element.idcuenta
        });
      } else {
        out *= -1;
        if (out > 0) {
          cargos.push({
            num: numpa,
            idmaestro: element.desembolso_perfil,
            iddetalle: element.idperfilcuentadetalle,
            value: out,
            id: element.idcuenta
          });
        }
      }

    } catch (error) {
      var e = new Error(error.message);
      e.name = '<div  v-tipo="titulo"><span style="font-weight: 400;">' + error.name + ' in </span>' + element.valor_abc + '</div> ';
      e.stack = error.stack;
      throw e;
    }
  }
  return cargos;
}
export function _mf36265_25421(arrayformulas, capitalin, cuo, interesin, interesdifin, mon, numpa, tcuo,cuoota,tipodecambio) {
  closeRam();
  window.total_cuotas = tcuo;
  window.total_refinanciar = 0;
  window.segurodesgravamen = 0;
  window.tipodecambio = 1;
  window.monto = (mon);
  window.cuota = (cuoota);
  window.cuotaf = (cuo);
  window.prestamos = 0;

  window.capital = capitalin;
  window.intereses = interesin;
  window.interes_diferido = interesdifin;
 // console.log('cobranza');
 // console.log(arrayformulas);
  var cargos = [];
  for (var index in arrayformulas) {
    try {
      var element = arrayformulas[index];

   //   if (element.iscargo) {
    //    jQuery.globalEval(element.valor_abc + '=0;');
    //  } else {
        jQuery.globalEval(element.valor_abc + '=' + redondeo_valor(eval(element.formula)) + ';');
    //  }
      var out = parseFloat(eval(element.valor_abc))*tipodecambio; 
      out = parseFloat(redondeo_valor(out)); 
      //console.log(element.valor_abc + '=' + out); 
      if (out > 0) {
        if (element.tipocargo === 'h') {
          out *= -1;
        }
        cargos.push({
          num: numpa,
          idmaestro: element.cobranza_perfil,
          iddetalle: element.idperfilcuentadetalle,
          value: out,
          id: element.idcuenta
        });
      } else {
        out *= -1;
        if (out > 0) {
          cargos.push({
            num: numpa,
            idmaestro: element.cobranza_perfil,
            iddetalle: element.idperfilcuentadetalle,
            value: out,
            id: element.idcuenta
          });
        }
      }

    } catch (error) {
      var e = new Error(error.message);
      e.name = '<div  v-tipo="titulo"><span style="font-weight: 400;">' + error.name + ' in </span>' + element.valor_abc + '</div> ';
      e.stack = error.stack;
      throw e;
    }
  }
  return cargos;
}
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** 

  __  _        
 / _|(_)       
| |_  _  _ __  
|  _|| || '_ \ 
| |  | || | | |
|_|  |_||_| |_| 
 * *************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */
/**************************************************************************************************************************************** */