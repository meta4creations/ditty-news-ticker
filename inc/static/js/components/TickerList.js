import { TickRow } from './TickRow';
import { TickEditor } from './TickEditor';
import { AddTickButton } from './AddTickButton';

export class TickerList extends React.Component {
  
  constructor(props) {
    super(props);
    
    const initListItems = Object.keys(ditty_news_ticker_vars.dnt_ticks).map(function(key) {
			return <TickRow {...ditty_news_ticker_vars.dnt_types[key]} />
		}, this);
    
    this.state = {
			editorOpen: false,
			listItems: initListItems
		};
    
    this.openTickEditor = this.openTickEditor.bind(this);
    this.closeTickEditor = this.closeTickEditor.bind(this);
    this.addTickRow = this.addTickRow.bind(this);
  }
  
  openTickEditor() {
	  
	  this.setState({
			editorOpen: true,
		});
  }
  
  closeTickEditor() {
	  
	  this.setState({
			editorOpen: false,
		});
  }
  
  addTickRow( key ) {
	  
	  let listItems = this.state.listItems;  
	 	if( !Array.isArray(listItems) ) {
		 	listItems = [];
	 	} 

    listItems.push(<TickRow {...ditty_news_ticker_vars.dnt_types[key]} />);  
  
    this.setState({
	    listItems: listItems
	  });
  }
  
  render() {
	  
	  if( this.state.editorOpen ) {
		  
		  return (
		    <div class="dnt-ticks">
				  <ul class="dnt-ticks__list">
				  	{this.state.listItems}
				  </ul>
				  <AddTickButton openTickEditor={this.openTickEditor} />
				  <TickEditor closeTickEditor={this.closeTickEditor} addTickRow={this.addTickRow} />
			  </div> 
		  );
		  
	  } else {
		  
		  return (
		    <div class="dnt-ticks">
				  <ul class="dnt-ticks__list">
				  	{this.state.listItems}
				  </ul>
				  <AddTickButton openTickEditor={this.openTickEditor} />
			  </div>
		  );
	  }  
  }
  
}