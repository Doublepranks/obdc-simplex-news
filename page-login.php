<?php
/**
 * Template Name: Login (Login)
 *
 * A custom login page styled for the theme.
 *
 * @package ObDC-simplex-news
 */

// Redirect if already logged in.
if (is_user_logged_in()) {
	wp_safe_redirect(home_url());
	exit;
}

$error = '';

// Handle Login Submission
if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['obdc_login_nonce'])) {

	if (!wp_verify_nonce($_POST['obdc_login_nonce'], 'obdc_login_action')) {
		$error = 'Erro de segurança. Tente novamente.';
	} else {
		$creds = array(
			'user_login' => sanitize_text_field($_POST['log']),
			'user_password' => $_POST['pwd'], // Passwords should not be sanitized with text filters
			'remember' => isset($_POST['rememberme']),
		);

		$user = wp_signon($creds, is_ssl());

		if (is_wp_error($user)) {
			// Translate common error messages if needed, or use WP defaults
			// WP defaults are usually localized if the site language is set.
			// But specific messages can be overridden here for cleaner UI.
			if ('incorrect_password' === $user->get_error_code()) {
				$error = 'A senha que você digitou está incorreta.';
			} elseif ('invalid_username' === $user->get_error_code() || 'invalid_email' === $user->get_error_code()) {
				$error = 'Não encontramos uma conta com esse usuário ou e-mail.';
			} else {
				$error = $user->get_error_message();
			}
		} else {
			wp_safe_redirect(home_url());
			exit;
		}
	}
}

get_header();
?>

<main id="main" class="site-main" role="main">
	<div class="wrap">
		<div class="auth-container">
			<div class="auth-card">
				<header class="auth-card__header">
					<h1 class="auth-card__title"><?php esc_html_e('Bem-vindo de volta', 'obdc-simplex-news'); ?></h1>
					<p class="auth-card__subtitle">

						<?php esc_html_e('Faça login para acessar sua conta.', 'obdc-simplex-news'); ?>
					</p>
				</header>

				<?php if (!empty($error)): ?>
					<div class="auth-message auth-message--error">
						<p><?php echo esc_html($error); ?></p>
					</div>
				<?php endif; ?>

				<form method="post" class="auth-form" action="">
					<div class="form-group">
						<label for="user_login"><?php esc_html_e('Usuário ou E-mail', 'obdc-simplex-news'); ?></label>
						<input type="text" name="log" id="user_login"
							value="<?php echo isset($_POST['log']) ? esc_attr($_POST['log']) : ''; ?>" required>
					</div>

					<div class="form-group">
						<label for="user_pass"><?php esc_html_e('Senha', 'obdc-simplex-news'); ?></label>
						<input type="password" name="pwd" id="user_pass" required>
					</div>

					<div class="form-group form-group--checkbox">
						<label for="rememberme">
							<input name="rememberme" type="checkbox" id="rememberme" value="forever">
							<?php esc_html_e('Lembrar de mim', 'obdc-simplex-news'); ?>
						</label>
					</div>

					<?php wp_nonce_field('obdc_login_action', 'obdc_login_nonce'); ?>

					<div class="form-actions">
						<button type="submit"
							class="btn btn--primary"><?php esc_html_e('Entrar', 'obdc-simplex-news'); ?></button>
					</div>
				</form>

				<div class="auth-footer">
					<p><a
							href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Esqueceu sua senha?', 'obdc-simplex-news'); ?></a>
					</p>
					<p><?php esc_html_e('Ainda não tem uma conta?', 'obdc-simplex-news'); ?> <a
							href="/register"><?php esc_html_e('Cadastrar', 'obdc-simplex-news'); ?></a></p>
				</div>
			</div>
		</div>
	</div>
</main>

<style>
	/* Scoped styles for the auth pages (Login/Register) */
	.auth-container {
		display: flex;
		justify-content: center;
		padding: 60px 0;
		min-height: 60vh;
		align-items: flex-start;
	}

	.auth-card {
		background: #fff;
		width: 100%;
		max-width: 480px;
		padding: 40px;
		border-radius: 20px;
		box-shadow: var(--shadow, 0 10px 15px -3px rgba(0, 0, 0, 0.1));
		border: 1px solid var(--border, #e2e8f0);
	}

	.auth-card__header {
		text-align: center;
		margin-bottom: 30px;
	}

	.auth-card__title {
		font-size: 2rem;
		margin: 0 0 10px;
		color: var(--brand, #0f172a);
		font-family: var(--font-heading, serif);
	}

	.auth-card__subtitle {
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

	.form-group input[type="text"],
	.form-group input[type="email"],
	.form-group input[type="password"] {
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

	.form-group--checkbox label {
		display: flex;
		align-items: center;
		gap: 8px;
		font-weight: normal;
		cursor: pointer;
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

	.auth-message {
		padding: 16px;
		border-radius: 8px;
		margin-bottom: 24px;
		font-size: 0.95rem;
		line-height: 1.5;
	}

	.auth-message--error {
		background-color: #fef2f2;
		color: #991b1b;
		border: 1px solid #fecaca;
	}

	.auth-footer {
		margin-top: 30px;
		text-align: center;
		font-size: 0.9rem;
		color: var(--muted, #64748b);
		border-top: 1px solid var(--border, #e2e8f0);
		padding-top: 20px;
	}

	.auth-footer p {
		margin-bottom: 8px;
	}

	.auth-footer a {
		color: var(--brand, #0f172a);
		font-weight: 600;
		text-decoration: none;
	}

	.auth-footer a:hover {
		text-decoration: underline;
	}
</style>

<?php
get_footer();
