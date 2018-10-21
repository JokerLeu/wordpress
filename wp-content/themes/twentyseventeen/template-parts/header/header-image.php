<?php
/**
 * 显示头部媒体
 * Displays header media
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<!--头部媒体代码在主题的\template-parts\header\header-image.php-->
<div class="custom-header">

		<div class="custom-header-media">
			<?php
            // 打印自定义头部的标记
            the_custom_header_markup();
            ?>
		</div>

	<?php
    // 将模板部件加载到模板中
    get_template_part( 'template-parts/header/site', 'branding' );
    ?>

</div><!-- .custom-header -->
