const cdm_core = {
    react:{
        fragment:React.Fragment,
        render: ReactDOM.render,
        useState:React.useState,
    }, 
    components:{},
    fn:{}
}



cdm_core.components.ImageWP = (props) => <img src={props.src} />;

cdm_core.components.Conditional = (props) => {
    if(props.condition){
        return  <cdm_core.react.fragment>
                    {props.children}
                </cdm_core.react.fragment>
    }else{
        return null;
    }
};

cdm_core.components.Collapsible = (props) => {
    const [open,setOpen] = cdm_core.react.useState(false)
    return <div className={"location-cell" + ((open)?' is-open':'' )} >
                <p onClick={() =>{setOpen(!open)}} className="cell-header"><span >{props.title}</span> <i  className={"fal fa-chevron-"+((open)?'up':'down' )}></i></p> 
                <div  className={ 'collapsible' + ((open)?' open':'' )}>
                    {props.children}
                </div>
            </div>
}


cdm_core.fn.docReady = function(fn)  {
    // see if DOM is already available
    if (document.readyState === "complete" || document.readyState === "interactive") {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}    

cdm_core.fn.HTMLEntities = (string) => {
    const el = document.createElement('span'); 
    el.innerHTML = string;
    document.body.append(el);
    const renderedString = el.textContent; 
    el.remove();
    return renderedString;
}



/*
 * forEach Polyfill
 *
 * 2015-12-27
 *
 * By Feifei Hang, http://feifeihang.info
 * Public Domain.
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 */
'use strict';
(function () {
  if (!Array.prototype.forEach) {
    Array.prototype.forEach = function forEach (callback, thisArg) {
      if (typeof callback !== 'function') {
        throw new TypeError(callback + ' is not a function');
      }
      var array = this;
      thisArg = thisArg || this;
      for (var i = 0, l = array.length; i !== l; ++i) {
        callback.call(thisArg, array[i], i, array);
      }
    };
  }
})();

// Source: https://github.com/jserz/js_piece/blob/master/DOM/ParentNode/append()/append().md
(function (arr) {
    arr.forEach(function (item) {
      if (item.hasOwnProperty('append')) {
        return;
      }
      Object.defineProperty(item, 'append', {
        configurable: true,
        enumerable: true,
        writable: true,
        value: function append() {
          var argArr = Array.prototype.slice.call(arguments),
            docFrag = document.createDocumentFragment();
  
          argArr.forEach(function (argItem) {
            var isNode = argItem instanceof Node;
            docFrag.appendChild(isNode ? argItem : document.createTextNode(String(argItem)));
          });
  
          this.appendChild(docFrag);
        }
      });
    });
  })([Element.prototype, Document.prototype, DocumentFragment.prototype]);

  
// Create Element.remove() function if not exist
if (!('remove' in Element.prototype)) {
    Element.prototype.remove = function() {
        if (this.parentNode) {
            this.parentNode.removeChild(this);
        }
    };
}