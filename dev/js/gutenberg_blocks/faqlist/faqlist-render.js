
cdm_core.fn.docReady(()=>build_faqlist())

const build_faqlist = () => {
    if(document.querySelector('.wp-block-exm-faqlist')){
        const {Collapsible} = cdm_core.components;
        
        const items = faqlist_data.faqs.map(x => <Collapsible title={cdm_core.fn.HTMLEntities(x.question)}><p>{x.reponse}</p></Collapsible>)


        cdm_core.react.render(<FaqList items={items} />,document.querySelector('.wp-block-exm-faqlist'));
    }

}

const FaqList = (props)=>{
    const [page,setPage] = cdm_core.react.useState(1);
    const max_per_page =  5 ;
    const nb_show = page * max_per_page;
    const Fragment = cdm_core.react.fragment;
    const {Conditional} = cdm_core.components;
    
    return  <Fragment>
                {props.items.slice(0,nb_show)}
                <Conditional condition={nb_show<props.items.length} >
                    <button onClick={()=>setPage(page+1)} >{faqlist_data.lang.more}</button>
                </Conditional>
            </Fragment>
}