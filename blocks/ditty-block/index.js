/**
 * Block dependencies
 */
import icons from './icon';
import './style.scss';
import './editor.scss';

/**
 * Internal block libraries
 */
const { __, sprintf } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { Fragment } = wp.element;
const { PanelBody, PanelRow, SelectControl, Spinner } = wp.components;
const { withSelect } = wp.data;

/**
 * Register block
 */
export default registerBlockType(
  'metaphorcreations/ditty-block',
  {
    title: __( 'Ditty', 'ditty-news-ticker' ),
    description: __( 'Display your Ditty within the content of the post.', 'ditty-news-ticker' ),
    category: 'widgets',
    icon: {
      //background: '#39b44a',
      src: icons.iconGreen,
    },   
    keywords: [
      __( 'Ticker', 'ditty-news-ticker' ),
      __( 'Display', 'ditty-news-ticker' ),
      __( 'Content', 'ditty-news-ticker' ),
    ],
    supports: {
      html: false,
    },
    attributes: {
      ditty: {
        type: 'string',
      },
      display: {
        type: 'string',
      }
    },
    edit: withSelect( select => {
        const { getEntityRecords } = select( 'core' );
        return {
          dittys: getEntityRecords( 'postType', 'ditty-news-ticker', { per_page: -1 } ),
        };
      } )( ( { attributes: { ditty, display }, dittys, className, isSelected, setAttributes } ) => {

      let ditty_posts = null;
      if ( dittys ) {
        ditty_posts = dittys.map( ditty_post => {
          return ( { key: ditty_post.id, value: ditty_post.id, label: ditty_post.title.raw } );
        } );
        ditty_posts.unshift( { key: 'selectDittyTicker', value: '', label:  __( 'Select a Ticker', 'ditty-news-ticker' ) } );
      }
      
      const display_posts = dittyBlocksEditorVars.displays.map( data => {
        const value = data.type_id + '--' + data.display_id;
        const label = data.display_label + ' (' + data.type_label + ')';
        return ( { key: value, value: value, label: label } );
      } );
      display_posts.unshift( { key: 'useDefaultDisplay', value: '', label:  __( 'Use Default Display', 'ditty-news-ticker' ) } );
      
      let currentDisplay = '';
      if ( display ) {
        for ( let i = 0; i < display_posts.length; i++ ) {
          if ( display === display_posts[i].value ) {
            currentDisplay = display_posts[i].label;
          }
        }
      }
      if ( '' === currentDisplay ) {
        currentDisplay = 'Default Display';
      }
      
      return [
        <InspectorControls key='dittySelectTicker'>
          <PanelBody>
            { ditty_posts
              ?
              <SelectControl
                label={ __( 'Ditty', 'ditty-news-ticker' ) }
                value={ ditty }
                options={ ditty_posts }
                onChange={ ditty => setAttributes( { ditty } ) }
              />
              :
              <Fragment><Spinner />{ __( 'Loading Tickers', 'ditty-news-ticker' ) }</Fragment>  
            }
            { display_posts
              ?
              <SelectControl
                label={ __( 'Display', 'ditty-news-ticker' ) }
                value={ display }
                options={ display_posts }
                onChange={ display => setAttributes( { display } ) }
              />
              :
              <Fragment><Spinner />{ __( 'Loading Displays', 'ditty-news-ticker' ) }</Fragment>  
            }
          </PanelBody>
        </InspectorControls>,
        <div key="dittyBlockViewTicker" className={ className }>
          {
            ( ! ditty || isSelected ) ? 
              <Fragment>
                <div className="wp-block-metaphorcreations-ditty-block__info">
                  {icons.logoBlack}
                  <div className="wp-block-metaphorcreations-ditty-block__vals">{ __( 'ID:', 'ditty-news-ticker' ) } <strong>{ ditty }</strong></div>
                  <div className="wp-block-metaphorcreations-ditty-block__vals">{ __( 'Display:', 'ditty-news-ticker' ) } <strong>{ currentDisplay }</strong></div>
                </div>
                <div className="wp-block-metaphorcreations-ditty-block__controls">
                  <SelectControl
                    label={ __( 'ID:', 'ditty-news-ticker' ) }
                    labelPosition = 'side'
                    value={ ditty }
                    options={ ditty_posts }
                    onChange={ ditty => setAttributes( { ditty } ) }
                  />
                  <SelectControl
                    label={ __( 'Display:', 'ditty-news-ticker' ) }
                    labelPosition = 'side'
                    value={ display }
                    options={ display_posts }
                    onChange={ display => setAttributes( { display } ) }
                  />
                </div>
              </Fragment>
            : (
              <div className="wp-block-metaphorcreations-ditty-block__info">
                {icons.logoBlack}
                <div className="wp-block-metaphorcreations-ditty-block__vals">{ __( 'ID:', 'ditty-news-ticker' ) } <strong>{ ditty }</strong></div>
                <div className="wp-block-metaphorcreations-ditty-block__vals">{ __( 'Display:', 'ditty-news-ticker' ) } <strong>{ currentDisplay }</strong></div>
              </div>
            )
            
          }
        </div>
      ];
    } ), // end edit
    save: props => {
      return null;
    },
  },
);
