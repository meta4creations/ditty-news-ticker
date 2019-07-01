export class TickEditorHeader extends React.Component {
  render() {
    return (
    	<div class="dnt-tick-editor__header">
    		<i class={this.props.icon}></i>
				<h2>{this.props.heading}</h2>
				<p>{this.props.description}</p>
    	</div>
    );
  }
}