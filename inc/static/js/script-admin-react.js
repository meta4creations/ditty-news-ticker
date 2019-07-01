/* global ditty_news_ticker_vars:true */
/* //global CodeMirror:true */
import { TickerList } from './components/TickerList';

if( document.getElementById('dnt-ticks') ) {
	wp.element.render(<TickerList />, document.getElementById('dnt-ticks') );
}