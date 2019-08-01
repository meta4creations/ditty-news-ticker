import { TickEditorBackground } from './TickEditorBackground';
import { TickEditorHeader } from './TickEditorHeader';
import { TickEditorTypeOption } from './TickEditorTypeOption';
import { TickEditorFields } from './TickEditorFields';

export class TickEditor extends React.Component {
	
	constructor(props) {
		super(props);	
		
		this.defaultState = {
			icon				: 'fas fa-exclamation-triangle',
			type				: '',
			label				: 'Select a tick type',
			description	: 'Select a tick type below to learn what it does.',
			view				: 'selectType'
		};	
		this.state = this.defaultState;	

		this.previewType 			= this.previewType.bind(this);
		this.selectType 			= this.selectType.bind(this);
		this.goBack 					= this.goBack.bind(this);
		this.closeTickEditor 	= this.closeTickEditor.bind(this);
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
			view				: 'newTickFields'
		});
	}
	
	goBack() {
		
		const view = this.state.view;
		let newView = view;
		
		switch( this.state.view ) {
			case 'newTickFields':
				newView = 'selectType';
				break;
		}
		
		this.setState({
			view : newView
		});
	}
	
	closeTickEditor() {
		this.props.closeTickEditor();
	}
	
  render() {
	  
	  let editorPanel = '';
		
		switch( this.state.view ) {
			case 'selectType':
				
				const typeOptions = Object.keys(ditty_news_ticker_vars.dnt_types).map(function(key) {
					return <TickEditorTypeOption {...ditty_news_ticker_vars.dnt_types[key]} previewType={this.previewType} selectType={this.selectType} isActive={(ditty_news_ticker_vars.dnt_types[key].type === this.state.type)} />
				}, this);
				
				editorPanel = (
					<div class={'dnt-tick-editor__content dnt-tick-editor__content--'+this.state.view}>
						<div class="dnt-tick-editor__content__inner">
							{typeOptions}
						</div>
					</div>
				);
				break;
				
			case 'newTickFields':
				
				editorPanel = (
					<TickEditorFields type={this.state.type} closeTickEditor={this.closeTickEditor} />
				);
				break;
				
			case 'editTickFields':
				
				editorPanel = (
					<TickEditorFields type={this.state.type} closeTickEditor={this.closeTickEditor} />
				);
				break;
		}

    return (
    	<div class="dnt-tick-editor">
    		<TickEditorBackground />
    		<div class="dnt-tick-editor__container">
    			<TickEditorHeader view={this.state.view} icon={this.state.icon} heading={this.state.label} description={this.state.description} goBack={this.goBack} closeTickEditor={this.closeTickEditor} />
    			{editorPanel}
    		</div>
    	</div>
    );
  }
}