<?php
/**
 * Template Name: Cadastro (Registration)
 *
 * A custom registration page styled for the theme.
 *
 * @package ObDC-simplex-news
 */

// Redirect if already logged in.
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url() );
	exit;
}

$errors = array();
$success = false;

// Handle Form Submission
if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['obdc_register_nonce'] ) ) {

	if ( ! wp_verify_nonce( $_POST['obdc_register_nonce'], 'obdc_register_action' ) ) {
		$errors[] = 'Erro de segurança. Tente novamente.';
	} else {
		$username = sanitize_user( $_POST['username'] );
		$email    = sanitize_email( $_POST['email'] );

		if ( empty( $username ) || empty( $email ) ) {
			$errors[] = 'Por favor, preencha todos os campos.';
		}

		if ( username_exists( $username ) ) {
			$errors[] = 'Este nome de usuário já está em uso.';
		}

		if ( email_exists( $email ) ) {
			$errors[] = 'Este e-mail já está cadastrado.';
		}

		if ( empty( $errors ) ) {
			// Create user
			$random_password = wp_generate_password( 12, false );
			$user_id = wp_create_user( $username, $random_password, $email );

			if ( ! is_wp_error( $user_id ) ) {
				// Send email with password (standard WP behavior)
				wp_new_user_notification( $user_id, null, 'both' );
				$success = true;
			} else {
				$errors[] = 'Erro ao criar conta: ' . $user_id->get_error_message();
			}
		}
	}
}

get_header();
?>

<main id="main" class="site-main" role="main">
	<div class="wrap">
		<div class="register-container">
			<div class="register-card">
				<header class="register-card__header">
					<h1 class="register-card__title"><?php esc_html_e( 'Criar Conta', 'obdc-simplex-news' ); ?></h1>
					<p class="register-card__subtitle"><?php esc_html_e( 'Junte-se à nossa comunidade para acessar recursos exclusivos.', 'obdc-simplex-news' ); ?></p>
				</header>

				<?php if ( $success ) : ?>
					<div class="register-message register-message--success">
						<p><strong><?php esc_html_e( 'Cadastro realizado com sucesso!', 'obdc-simplex-news' ); ?></strong></p>
						<p><?php esc_html_e( 'Verifique seu e-mail para obter sua senha e acessar sua conta.', 'obdc-simplex-news' ); ?></p>
						<div class="register-actions">
							<a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn btn--primary"><?php esc_html_e( 'Fazer Login', 'obdc-simplex-news' ); ?></a>
						</div>
					</div>
				<?php else : ?>

					<?php if ( ! empty( $errors ) ) : ?>
						<div class="register-message register-message--error">
							<?php foreach ( $errors as $error ) : ?>
								<p><?php echo esc_html( $error ); ?></p>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<form method="post" class="register-form" action="">
						<div class="form-group">
							<label for="username"><?php esc_html_e( 'Nome de Usuário', 'obdc-simplex-news' ); ?></label>
							<input type="text" name="username" id="username" value="<?php echo isset( $username ) ? esc_attr( $username ) : ''; ?>" required>
						</div>

						<div class="form-group">
							<label for="email"><?php esc_html_e( 'E-mail', 'obdc-simplex-news' ); ?></label>
							<input type="email" name="email" id="email" value="<?php echo isset( $email ) ? esc_attr( $email ) : ''; ?>" required>
							<p class="form-hint"><?php esc_html_e( 'Uma senha será enviada para o seu endereço de e-mail.', 'obdc-simplex-news' ); ?></p>
						</div>

						<?php wp_nonce_field( 'obdc_register_action', 'obdc_register_nonce' ); ?>

						<div class="form-actions">
							<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Cadastrar', 'obdc-simplex-news' ); ?></button>
						</div>
					</form>

					<div class="register-footer">
						<p><?php esc_html_e( 'Já tem uma conta?', 'obdc-simplex-news' ); ?> <a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Entrar', 'obdc-simplex-news' ); ?></a></p>
					</div>

				<?php endif; ?>
			</div>
		</div>
	</div>
</main>

<style>
/* Scoped styles for the registration page to ensure coherence */
.register-container {
	display: flex;
	justify-content: center;
	padding: 60px 0;
	min-height: 60vh;
	align-items: flex-start;
}

.register-card {
	background: #fff;
	width: 100%;
	max-width: 480px;
	padding: 40px;
	border-radius: 20px;
	box-shadow: var(--shadow, 0 10px 15px -3px rgba(0, 0, 0, 0.1));
	border: 1px solid var(--border, #e2e8f0);
}

.register-card__header {
	text-align: center;
	margin-bottom: 30px;
}

.register-card__title {
	font-size: 2rem;
	margin: 0 0 10px;
	color: var(--brand, #0f172a);
	font-family: var(--font-heading, serif);
}

.register-card__subtitle {
	color: var(--muted, #64748b);
	font-size: 1rem;
	margin: 0;
}

.form-group {
	margin-bottom: 20px;
}

.form-group label {
	display: block;
	margin-bottom: 8px;
	font-weight: 600;
	color: var(--ink, #334155);
	font-size: 0.95rem;
}

.form-group input {
	width: 100%;
	padding: 12px 16px;
	border: 2px solid var(--border, #cbd5e1);
	border-radius: 8px;
	font-size: 1rem;
	transition: border-color 0.2s, box-shadow 0.2s;
	background: #f8fafc;
}

.form-group input:focus {
	outline: none;
	border-color: var(--brand, #0f172a);
	background: #fff;
	box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
}

.form-hint {
	font-size: 0.85rem;
	color: var(--muted, #94a3b8);
	margin-top: 6px;
}

.form-actions {
	margin-top: 30px;
}

.btn {
	display: inline-flex;
	justify-content: center;
	align-items: center;
	padding: 14px 24px;
	border-radius: 99px;
	font-weight: 700;
	text-align: center;
	cursor: pointer;
	transition: all 0.2s ease;
	text-decoration: none;
	width: 100%;
	border: none;
	font-size: 1rem;
}

.btn--primary {
	background-color: var(--brand, #0f172a);
	color: #fff;
}

.btn--primary:hover {
	background-color: var(--accent, #334155);
	transform: translateY(-1px);
}

.register-message {
	padding: 16px;
	border-radius: 8px;
	margin-bottom: 24px;
	font-size: 0.95rem;
	line-height: 1.5;
}

.register-message--error {
	background-color: #fef2f2;
	color: #991b1b;
	border: 1px solid #fecaca;
}

.register-message--success {
	background-color: #f0fdf4;
	color: #166534;
	border: 1px solid #bbf7d0;
	text-align: center;
}

.register-footer {
	margin-top: 24px;
	text-align: center;
	font-size: 0.9rem;
	color: var(--muted, #64748b);
	border-top: 1px solid var(--border, #e2e8f0);
	padding-top: 20px;
}

.register-footer a {
	color: var(--brand, #0f172a);
	font-weight: 600;
	text-decoration: none;
}

.register-footer a:hover {
	text-decoration: underline;
}
</style>

<?php
get_footer();
