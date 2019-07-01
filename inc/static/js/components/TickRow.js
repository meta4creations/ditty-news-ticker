export class TickRow extends React.Component {
  render() {
    return (
    	<li>
    		<i class={this.props.icon}></i>
    		{this.props.label}
    	</li>
    );
  }
}