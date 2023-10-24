const { registerBlockType } = wp.blocks;

const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const { PanelBody,SelectControl   } = wp.components;

const {Conditional} = cdm_core.components

const CompareListItem = (props) => {
    if(props.edit){
        return <div>
                    <span><i onClick={()=>props.editFn(props.id,'bool',!props.bool)} className={(props.bool)?'fal fa-check-circle':'fal fa-times-circle' }></i><input onInput={(e)=>props.editFn(props.id,'text',e.target.value)} value={props.text}/></span>
                    <span><i onClick={()=>props.editFn(props.id,'bool2',!props.bool2)} className={(props.bool2)?'fal fa-check-circle':'fal fa-times-circle' }></i><input onInput={(e)=>props.editFn(props.id,'text2',e.target.value)} value={props.text2}/></span>
                    <span><i onClick={()=>props.deleteFn(props.id)} className="fal fa-trash-alt"></i></span>
                </div>
    }else{
        return  <div>
                    <span>
                        <Conditional condition={props.text !== ''}>
                            <i className={(props.bool)?'fal fa-check-circle':'fal fa-times-circle' }></i><span>{props.text}</span>
                        </Conditional>
                    </span>
                    <span>
                        <Conditional condition={props.text2 !== ''}>
                            <i className={(props.bool2)?'fal fa-check-circle':'fal fa-times-circle' }></i><span>{props.text2}</span>
                        </Conditional>
                    </span>
                </div>
    }
}

registerBlockType(comparelist_data.suffix+'/compare-list',{
	title:comparelist_data.lang.title,
	icon: 'fullscreen-alt',
    category: 'layout',
    className:'compare-list',
 
	attributes:{
		rows:{
            type:'array',
            default : [
                {
                    text:'',
                    bool:true,
                    text2:'',
                    bool2:true
                }
            ]
        }
    },

    
	edit:(props)=>{


        const deleteRow = (i) => {
            props.setAttributes({
                rows:props.attributes.rows.filter((el,index)=> index!==i)
            })
        }

        const editRow = (i,prop,val)=>{
            props.setAttributes({
                rows: props.attributes.rows.map((x,index) => {
                    if(i === index){
                        x[prop] = val;
                    }
                    return x
                })
            })
        }

        const itemlist = props.attributes.rows.map((x,i)=>{
            return <CompareListItem edit={props.isSelected} editFn={editRow} deleteFn={deleteRow} id={i} {...x} />
        });

        

        const addRow = ()=>{
            props.setAttributes({
                rows: [...props.attributes.rows,{
                    text:'',
                    bool:true,
                    text2:'',
                    bool2:true
                }]
            })
        }

		return 	<Fragment>
                    <div className={'wp-block-exm-compare-list isEditor'} >
                        {itemlist}
                    </div>
                    <Conditional condition={props.isSelected}>
                        <button onClick={addRow} >Add list Row</button>
                    </Conditional>
				</Fragment>
	},
    save:(props) =>{

        const itemlist = props.attributes.rows.map((x,i)=>{
            return <CompareListItem edit={false} {...x} />
        });

        return  <div className={props.className} >
                    {itemlist}
                </div>
    },
});