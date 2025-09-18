<?php
/**
 * O template para exibir a área de comentários.
 *
 * @package ObDC-simplex-news
 */

/*
 * Se o post requer uma senha e o visitante não a inseriu, retornar antes de carregar os comentários.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// Você pode começar a personalizar este template aqui.
	// Por enquanto, vamos usar o template padrão do WordPress.
	comment_form();
	?>

</div><!-- #comments -->