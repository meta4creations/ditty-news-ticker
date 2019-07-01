import { TickEditorBackground } from './TickEditorBackground';
import { TickEditorHeader } from './TickEditorHeader';
import { TickEditorTypeOption } from './TickEditorTypeOption';
import { TypeFields } from './TypeFields';

export class TickEditor extends React.Component {
	
	constructor(props) {
		super(props);	
		
		this.defaultState = {
			icon				: 'fas fa-exclamation-triangle',
			type				: '',
			label				: 'Select a tick type',
			description	: 'Select a tick type below to learn what it does.',
			view				: 'select-type'
		};	
		this.state = this.defaultState;	
		this.previewType = this.previewType.bind(this);
		this.selectType = this.selectType.bind(this);
		this.closeTickEditor = this.closeTickEditor.bind(this);
	}
	
	previewType( newIcon, newType, newLabel, newDescription ) {
		
		let icon 				= newIcon ? newIcon : this.defaultState.icon;
		let type 				= newType ? newType : this.defaultState.type;
		let label 			= newLabel ? newLabel : this.defaultState.label;
		let description = newDescription ? newDescription : this.defaultState.description;
		
		this.setState({
			icon				: icon,
			type				: type,
			label				: label,
			description	: description
		});
	}
	
	selectType( type ) {
		
		
		
		this.setState({
			view				: ditty_news_ticker_vars.dnt_types[type].icon,
			type				: ditty_news_ticker_vars.dnt_types[type].type,
			label				: ditty_news_ticker_vars.dnt_types[type].label,
			description	: ditty_news_ticker_vars.dnt_types[type].description,
			fields			: '',
			view				: 'loading-fields'
		});
		
		let url = 'http://dittynewsticker.localhost/wp-json/dnt-api/v1/fields/default';
		
		if( url !== '0' ) {
      let json = fetch(url)
      .then(response => { console.log( response.json() ); })
    } else {
      console.log('hmmmmm');
    }

		//this.props.typeSelected( type );
		//this.props.addTickRow();
	}
	
	closeTickEditor() {
		
		this.props.closeTickEditor();
	}
	
  render() {
	  
	  let editorPanel = '';
		
		switch( this.state.view ) {
			case 'select-type':
				
				const typeOptions = Object.keys(ditty_news_ticker_vars.dnt_types).map(function(key) {
					return <TickEditorTypeOption {...ditty_news_ticker_vars.dnt_types[key]} previewType={this.previewType} selectType={this.selectType} isActive={(ditty_news_ticker_vars.dnt_types[key].type === this.state.type)} />
				}, this);
				
				editorPanel = (
					<div class="dnt-tick-editor__content__inner">
						{typeOptions}
					</div>
				);
				break;
				
			case 'loading-fields':
				
				editorPanel = (
					'LOADING FIELDS NOW'
				);
				break;
				
			case 'edit-fields':
				
				editorPanel = (
					'FIELDS HAVE LOADED!'
				);
				break;
		}
		if( this.state.view )
	  
    return (
    	<div class="dnt-tick-editor">
    		<TickEditorBackground closeTickEditor={this.closeTickEditor} />
    		<div class="dnt-tick-editor__container">
    			<TickEditorHeader icon={this.state.icon} heading={this.state.label} description={this.state.description} />
    			<div class={'dnt-tick-editor__content ' + this.state.view}>
	    			{editorPanel}
	    		</div>
    		</div>
    	</div>
    );
  }
}