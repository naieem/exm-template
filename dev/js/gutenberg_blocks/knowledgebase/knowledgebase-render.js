
cdm_core.fn.docReady(()=>build_knowledgebase())


const build_knowledgebase = () => {
  
    
    if(document.querySelector('.wp-block-exm-knowledgebase')){
        cdm_core.react.render(<Knowledgebase docs={knowledgebase_data.docs} />,document.querySelector('.wp-block-exm-knowledgebase'));
    }

}



const Knowledgebase = (props)=>{
    const Fragment = cdm_core.react.fragment;
    const [currentcat,setCurrentcat] = cdm_core.react.useState(null);
    const [router,setRouter] = cdm_core.react.useState([]);
    const {Conditional} = cdm_core.components;
    const search = '';

    // passing an empty array as second argument triggers the callback in useEffect
    // only after the initial render thus replicating `componentDidMount` lifecycle behaviour
    React.useEffect(() => {
        buildForm();
        if(document.querySelector('.wrap-extra') !== undefined){
            let hr = document.createElement('hr');
            hr.className = "p-0 m-0 has-color-2-background-color has-background";
            document.querySelector('.wrap-extra').parentNode.insertBefore(hr, document.querySelector('.wrap-extra').nextSibling);
        }
    }, []);
    React.useEffect(() => {
        fil_ariane();
        
    }) ;

    const buildForm = function (){         
        if(document.querySelector('banner-extra.content .right') !== undefined){
            cdm_core.react.render(
                <div class="search_box"> 
                    <h4>{knowledgebase_data.lang.howhelp}</h4>
                    <div class="form-wrapper">
                        <input type="text" name="s" id="search" onChange={onKeyupHandlerSearch}  placeholder={knowledgebase_data.lang.search} /><button onClick={onClickHandlerSearch} class="search-knowledge"><i class="fal fa-search"></i></button>
                    </div>
                </div>
            ,document.querySelector('.banner-extra.content .right'));
        }
    };



    const fil_ariane = function(){
        const base = [<a href="#" onClick={(e)=>{e.preventDefault();navigateHistory(null)}} >{knowledgebase_data.lang.ariane_base}</a>]
        const fil = router.flatMap(x=>{
            const doc = props.docs.filter(y=>y.ID == x);
            if(doc.length > 0){
                return [<i class="fal fa-angle-right" aria-hidden="true"></i>,<a onClick={(e)=>{e.preventDefault();navigateHistory(doc[0].ID)}} href={doc[0].ID} >{cdm_core.fn.HTMLEntities(doc[0].name)}</a>]
            }
        });
        let fil_ariane = base.concat(fil);
        if(currentcat){
            const doc = props.docs.filter(y=>y.ID == currentcat);
            if(doc.length > 0){
                fil_ariane = fil_ariane.concat([<i class="fal fa-angle-right" aria-hidden="true"></i>,<span>{cdm_core.fn.HTMLEntities(doc[0].name)}</span>]);
            }
        }

        if(document.querySelector('div.breadcrumb') !== undefined){
            cdm_core.react.render(
                <Fragment>
                    {fil_ariane}
                </Fragment>
            ,document.querySelector('div.breadcrumb'));
        }
    }


    window.onpopstate = function(event) {
        if(router.length > 0){
            event.preventDefault();
            back()
        }
    };
    

    const onClickHandlerSearch = () => {
        navigate(document.getElementById('search').value)
    }
    const onKeyupHandlerSearch = (e) => {
        navigate(e.target.value)
    }
    const get_current_level = () => {
        let sorted = [];

        if(currentcat == null){
            sorted = props.docs.filter(x=>(x.parent_category == null || x.parent_category == 0) && x.is_category == true);
        }else if(typeof currentcat == 'string'){
            sorted = props.docs.filter(x=>(x.name.toLowerCase().replace(' ','').includes(currentcat.toLowerCase().replace(' ',''))));
        }else {
            sorted = props.docs.filter(x=>x.parent_category == currentcat );
        }
        return sorted;
    }

    const navigateHistory = (to)=>{
        
        const index = router.indexOf(to);
        setRouter(router.slice(0,index))
        setCurrentcat(to);
    }

    const navigate = (id)=>{
        document.querySelector('.banner-top').scrollIntoView(true)
        setRouter([...router,currentcat])
        setCurrentcat(id);
    }

    const back = ()=>{
        const to = router[router.length-1];
        setRouter(router.filter(x => x!=to))
        setCurrentcat(to);
    }

    const buildCells = ()=>{
        return get_current_level().map(doc=>{
            return <Knowledgebase_cell clickFn={navigate} image={doc.image} is_category={doc.is_category} file={doc.file} title={doc.name} id={doc.ID} />
        })
    }


    return  <Fragment>
                <Conditional condition={router.length > 0} >
                    <button onClick={back} >{knowledgebase_data.lang.back}</button>
                </Conditional>
                <Conditional condition={typeof currentcat == 'string'} >
                    <p class="empty" > {currentcat}</p>
                </Conditional>
                <Conditional condition={get_current_level().length > 0} >
                    <div>{buildCells()}</div>
                </Conditional>
                <Conditional condition={get_current_level().length == 0 && typeof currentcat != 'string'} >
                    <p class="empty" > {knowledgebase_data.lang.empty}</p>
                </Conditional>
                <Conditional condition={get_current_level().length == 0 && typeof currentcat == 'string'} >
                    <p class="empty" > {knowledgebase_data.lang.search_empty}</p>
                </Conditional>
            </Fragment>
}

const Knowledgebase_cell = (props) => {

    const clickBehavior = () => {
        if(props.file == null || props.file == false){
            props.clickFn(props.id)
        }else{
            open(props.file.url)
        }
    }

    const {ImageWP} = cdm_core.components;
    return  <div onClick={clickBehavior} > 
                <ImageWP src={props.image} />
                <span>{cdm_core.fn.HTMLEntities(props.title)}</span>
            </div>
}

