export class TickEditorBackground extends React.Component {
	
	constructor(props) {
		super(props);	
		
		this.handleClick = this.handleClick.bind(this);
	}
	
	handleClick() {
		this.props.closeTickEditor();
	}
		
  render() {
    return (
    	<div class="dnt-tick-editor__background" onClick={this.handleClick}></div>
    );
  }
}