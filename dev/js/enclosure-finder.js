/* Enclosure finder scripts */

function in_array(needle, haystack) {
    for(var i in haystack) {
        if(haystack[i] == needle) return true;
    }
    return false;
}


const finder = {
    element:null,
    currentSize:"in",
    conversions: {
        in:{
            mm:{
                rate:25.4,
                modifier:(x)=> x.toFixed(2)
            }
            
        },
        mm:{
            in:{
                rate:0.0393701,
                modifier:(x)=> x.toFixed(0)
            }
            
        }
    },
    ready:function(){
        if (document.readyState === "complete" || document.readyState === "interactive") {
            setTimeout(finder.init, 1);
        } else { 
            document.addEventListener("DOMContentLoaded", finder.init);
        }
    },
    init:function(){
        finder.element = document.getElementById('EF');
        
        if(finder.element){
            finder.element.addEventListener('click',function(e){
                if(e.target.id == 'EF'){
                    finder.toggle();
                }
            })
            finder.element.querySelectorAll('[data-size]').forEach(btn => {
                btn.addEventListener('click',function(e){
                    finder.element.querySelectorAll('[data-size]').forEach(x=>{
                        x.classList.remove('selected')
                    })
                    e.target.classList.add('selected')
                    let clicked = e.target.getAttribute('data-size');
                    if(clicked != finder.currentSize){                 
                        if(in_array(clicked,Object.keys(finder.conversions))){

                            finder.element.querySelectorAll('.size-select option').forEach(option => {
                                let size_string = option.innerHTML;
                                let size_array = size_string.split('x');
                                for(let i = 0 ; i < size_array.length ; i++){
                                    size_array[i] = parseFloat(size_array[i]) * finder.conversions[finder.currentSize][clicked].rate;
                                    if(finder.conversions[finder.currentSize][clicked].modifier){
                                        size_array[i] = finder.conversions[finder.currentSize][clicked].modifier(size_array[i]);
                                    }
                                }
                                size_string = size_array[0]+' x '+size_array[1]+' x '+size_array[2];
                                option.innerHTML = size_string;
                            })
                            finder.currentSize = clicked
                            jQuery('.size-select').select2();
                        }

                    }
                })
            });
            document.querySelectorAll('.search-trigger, .close-search').forEach(btn => {
                btn.addEventListener('click',function(e){
                    e.preventDefault()
                    finder.toggle()
                })
            })
            finder.element.querySelector('.reset-finder').addEventListener('click',finder.reset)
            finder.element.querySelector('.finder-footer button').addEventListener('click',function(){document.querySelector('form.popup-content').submit()})
        }
    },
    reset: function(){
        finder.element.querySelectorAll('input:checked').forEach(x=>{
            x.checked = false;
        })
        finder.element.querySelectorAll('select').forEach(x=>{
            x.value = '';
            const e = new Event('change');
            x.dispatchEvent(e);
        })
    },
    toggle:function(){
        const timingAnim = 250;
        const el = this.element;
        if(el.classList.contains('open')){
            el.classList.add('open-out');
            el.classList.remove('open');
            setTimeout(function(){
                el.classList.remove('open-out');
            },timingAnim);
        }else{
            el.classList.add('open-in');
            setTimeout(function(){
                el.classList.add('open');
                el.classList.remove('open-in');

            },timingAnim);
        }
    }
}



finder.ready()

