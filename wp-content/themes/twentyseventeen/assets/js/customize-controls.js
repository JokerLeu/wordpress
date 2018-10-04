/**
 * 自定义控件窗口中的脚本。
 * Scripts within the customizer controls window.
 *
 * Contextually shows the color hue control and informs the preview
 * when users open or close the front page sections section.
 */

(function() {
	wp.customize.bind( 'ready', function() {

		// 只有在自定义颜色方案时才显示颜色色调控制。
		// Only show the color hue control when there's a custom color scheme.
		wp.customize( 'colorscheme', function( setting ) {
			wp.customize.control( 'colorscheme_hue', function( control ) {
				var visibility = function() {
					if ( 'custom' === setting.get() ) {
						control.container.slideDown( 180 );
					} else {
						control.container.slideUp( 180 );
					}
				};

				visibility();
				setting.bind( visibility );
			});
		});

		// 检测前页区段的展开（或关闭），以便我们可以相应地调整预览。
		// Detect when the front page sections section is expanded (or closed) so we can adjust the preview accordingly.
		wp.customize.section( 'theme_options', function( section ) {
			section.expanded.bind( function( isExpanding ) {

				// 如果您正在进入该节，则扩展名为true（true），如果您离开，则为false。
				// Value of isExpanding will = true if you're entering the section, false if you're leaving it.
				wp.customize.previewer.send( 'section-highlight', { expanded: isExpanding });
			} );
		} );
	});
})( jQuery );
