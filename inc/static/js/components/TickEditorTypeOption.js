export class TickEditorTypeOption extends React.Component {
	
	constructor(props) {
		super(props);
		this.handleMouseOver = this.handleMouseOver.bind(this);
		this.handleMouseOut = this.handleMouseOut.bind(this);
		this.handleMouseClick = this.handleMouseClick.bind(this);
	}
	
	handleMouseOver() {
		this.props.previewType(this.props.icon, this.props.type, this.props.label, this.props.description);	
	}
	
	handleMouseOut() {
		this.props.previewType();	
	}
	
	handleMouseClick() {
		this.props.selectType(this.props.type);	
	}
	
  render() {
    return (
    	<div class={this.props.isActive ? 'dnt-tick-editor__type-option active' : 'dnt-tick-editor__type-option'}>
    		<div class="dnt-tick-editor__type-option__link" onMouseOver={this.handleMouseOver} onMouseOut={this.handleMouseOut} onClick={this.handleMouseClick}>
	    		<i class={this.props.icon}></i>
	    		{this.props.label}
    		</div>
    	</div>
    );
  }
}