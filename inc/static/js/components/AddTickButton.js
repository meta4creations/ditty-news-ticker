export class AddTickButton extends React.Component {
	
	constructor(props) {
		super(props);	
	
		this.state = {
			label: ditty_news_ticker_vars.dnt_strings.add_new_tick
		};
		this.handleClick = this.handleClick.bind(this);
	}
	
	handleClick(e) {
		e.preventDefault();
		this.props.openTickEditor();
	}
	
  render() {
    return (
    	<a class="dnt-add-tick-button" href="#" onClick={this.handleClick}>{this.state.label}</a>
    );
  }
}