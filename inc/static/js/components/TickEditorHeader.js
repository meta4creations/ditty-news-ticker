export class TickEditorHeader extends React.Component {
	
	constructor(props) {
		super(props);
		this.handleBackClick = this.handleBackClick.bind(this);
		this.handleCloseClick = this.handleCloseClick.bind(this);
	}
	
	handleCloseClick(e) {
		e.preventDefault();
		this.props.closeTickEditor();
	}
	
	handleBackClick(e) {
		e.preventDefault();
		this.props.goBack();
	}
	
  render() {
	  
	  const backBtn = ( 'selectType' != this.props.view ) ? <a class="dnt-tick-editor__back" href="#" onClick={this.handleBackClick}><i class="fal fa-arrow-square-left"></i></a> : false;
	  
    return (
    	<div class="dnt-tick-editor__header">
    		<i class={this.props.icon}></i>
				<h2>{this.props.heading}</h2>
				<p>{this.props.description}</p>
				{backBtn}
				<a class="dnt-tick-editor__close" href="#" onClick={this.handleCloseClick}><i class="fal fa-window-close"></i></a>
    	</div>
    );
  }
}