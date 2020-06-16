<?php

/**
 * Provides hooks for displaying various email campaign sections
 */

/**
 * Email campaigns table class.
 */
class Noptin_Email_Campaigns_Admin {

	/**
	 *  Constructor function.
	 */
	function __construct() {

		// Display the newsletters page.
		add_action( 'noptin_email_campaigns_tab_newsletters', array( $this, 'show_newsletters' ) );
		add_action( 'noptin_newsletters_section_view_campaigns', array( $this, 'view_newsletter_campaigns' ) );
		add_action( 'noptin_newsletters_section_new_campaign', array( $this, 'render_email_campaign_form' ) );
		add_action( 'noptin_newsletters_section_edit_campaign', array( $this, 'render_email_campaign_form' ) );

		// Display the automations page.
		add_action( 'noptin_email_campaigns_tab_automations', array( $this, 'show_automations' ) );
		add_action( 'noptin_automations_section_view_campaigns', array( $this, 'view_automation_campaigns' ) );
		add_action( 'noptin_automations_section_edit_campaign', array( $this, 'render_automation_campaign_form' ) );

		// Maybe save campaigns.
		add_action( 'wp_loaded', array( $this, 'maybe_save_campaign' ) );
		add_action( 'wp_loaded', array( $this, 'maybe_save_automation_campaign' ) );

		// Maybe send a campaign.
		add_action( 'transition_post_status', array( $this, 'maybe_send_campaign' ), 100, 3 );

		// Delete campaign stats.
		add_action( 'delete_post', array( $this, 'maybe_delete_stats' ) );

	}

	/**
	 *  Displays the newsletters section
	 */
	function show_newsletters() {

		$sub_section = empty( $_GET['sub_section'] ) ? 'view_campaigns' : $_GET['sub_section'];

		/**
		 * Runs before displaying the newsletters section
		 */
		do_action( 'noptin_before_display_newsletters_section', $sub_section );

		/**
		 * Runs when displaying a specific newsletters section.
		 */
		do_action( "noptin_newsletters_section_$sub_section" );

		/**
		 * Runs after displaying the newsletters section
		 */
		do_action( 'noptin_after_display_newsletters_section', $sub_section );

	}

	/**
	 *  Displays a list of available newsletters
	 */
	function view_newsletter_campaigns() {
		$table = new Noptin_Email_Newsletters_Table();
		$table->prepare_items();

		$add_new_campaign_url = get_noptin_new_newsletter_campaign_url();

		?>
		<div class="wrap">
			<form id="noptin-newsletter-campaigns-table" method="GET">
				<input type="hidden" name="page" value="noptin-email-campaigns"/>
				<input type="hidden" name="section" value="newsletters"/>
				<div class="noptin-campaign-action-links">
					<a href="<?php echo $add_new_campaign_url; ?>" class="button-secondary create-new-campaign"><?php _e( 'Compose New Email', 'newsletter-optin-box' ); ?></a>
				</div>

				<?php $table->display(); ?>
				<p class="description"><?php _e( 'Use this page to send one-time emails to your email subscribers', 'newsletter-optin-box' ); ?></p>
			</form>
		</div>
		<?php

	}

	/**
	 *  Displays the campaign creation form
	 */
	function render_email_campaign_form() {

		$id       = empty( $_GET['id'] ) ? 0 : $_GET['id'];
		$campaign = false;

		if ( $id ) {
			$campaign = get_post( $id );
		}

		get_noptin_template( 'newsletter-campaign-form.php', compact( 'id', 'campaign' ) );

	}


	/**
	 *  Displays the automations section
	 */
	function show_automations() {

		$sub_section = empty( $_GET['sub_section'] ) ? 'view_campaigns' : $_GET['sub_section'];

		/**
		 * Runs before displaying the automations section
		 */
		do_action( 'noptin_before_display_automations_section', $sub_section );

		/**
		 * Runs when displaying a specific automations section.
		 */
		do_action( "noptin_automations_section_$sub_section" );

		/**
		 * Runs after displaying the automations section
		 */
		do_action( 'noptin_after_display_automations_section', $sub_section );

	}

	/**
	 *  Displays a list of available automations
	 */
	function view_automation_campaigns() {

		$triggers = $this->get_automation_triggers();
		$table    = new Noptin_Email_Automations_Table();
		$table->prepare_items();

		add_thickbox();

		?>
		<div class="wrap">
			<form id="noptin-automation-campaigns-table" method="GET">
				<input type="hidden" name="page" value="noptin-email-campaigns"/>
				<input type="hidden" name="section" value="automations"/>
				<div class="noptin-campaign-action-links">
					<a href="#" class="button-secondary noptin-create-new-automation-campaign"><?php _e( 'Create New Automation', 'newsletter-optin-box' ); ?></a>
				</div>

				<?php $table->display(); ?>
				<p class="description"><?php _e( 'Use this page to create emails that will be automatically emailed to your subscribers', 'newsletter-optin-box' ); ?></p>
			</form>
			<div id="noptin-create-automation" style="display:none;">
				<?php get_noptin_template( 'new-email-automations-popup.php', compact( 'triggers' ) ); ?>
			</div>
		</div>
		<?php

	}

	/**
	 *  Returns a list of all automations
	 */
	function get_automation_triggers() {

		$triggers = array(
			'post_notifications' => array(
				'title'          => __( 'Post Notifications', 'newsletter-optin-box' ),
				'description'    => __( 'Notify your subscribers everytime you publish new content.', 'newsletter-optin-box' ),
				'support_delay'  => __( 'After new content is published', 'newsletter-optin-box' ),
				'support_filter' => true,
			),
			'welcome_email'      => array(
				'title'          => __( 'Welcome Email', 'newsletter-optin-box' ),
				'description'    => __( 'Introduce yourself to new subscribers or set up a series of welcome emails to act as an email course.', 'newsletter-optin-box' ),
				'support_delay'  => __( 'After someone subscribes', 'newsletter-optin-box' ),
				'support_filter' => __( 'All new subscribers', 'newsletter-optin-box' ),
			),
			'subscriber_tag'     => array(
				'title'         => __( 'Subscriber Tag', 'newsletter-optin-box' ),
				'description'   => __( 'Send an email to a subscriber when you tag them.', 'newsletter-optin-box' ),
				'support_delay' => __( 'After a subscriber is tagged', 'newsletter-optin-box' ),
			),
			'previous_email'     => array(
				'title'         => __( 'Previous Email', 'newsletter-optin-box' ),
				'description'   => __( 'Send an email to a subscriber when they open or click on a link in another email.', 'newsletter-optin-box' ),
				'support_delay' => true,
			),
		);

		return apply_filters( 'noptin_email_automation_triggers', $triggers, $this );

	}

	/**
	 *  Displays the automation campaign creation form.
	 *
	 * @param int $id the form being rendered.
	 */
	function render_automation_campaign_form( $id = 0 ) {

		if ( empty( $id ) && empty( $_GET['id'] ) ) {
			return;
		}

		if ( empty( $id ) ) {
			$id = trim( $_GET['id'] );
		}

		// Prepare the campaign being edited.
		$campaign_id = absint( $id );
		$campaign    = get_post( $id );

		// Ensure this is an automation campaign.
		if ( ! is_noptin_campaign( $campaign, 'automation' ) ) {
			return;
		}

		// Prepare data.
		$automation_type = sanitize_text_field( stripslashes_deep( get_post_meta( $campaign_id, 'automation_type', true ) ) );
		$preview_text    = sanitize_text_field( stripslashes_deep( get_post_meta( $campaign_id, 'preview_text', true ) ) );
		$subject         = sanitize_text_field( stripslashes_deep( get_post_meta( $campaign_id, 'subject', true ) ) );
		$email_body      = wp_kses_post( stripslashes_deep( $campaign->post_content ) );
		$automations     = $this->get_automation_triggers();
		$supports_filter = ! empty( $automations[ $automation_type ] ) && ! empty( $automations[ $automation_type ]['support_filter'] );
		$automations     = $this->get_automation_triggers();

		// Load the automation campign form.
		include locate_noptin_template( 'automation-campaign-form.php' );

	}

	/**
	 *  Saves an automation campaign
	 */
	function maybe_save_automation_campaign() {

		if ( wp_doing_ajax() ) {
			return;
		}

		$admin = Noptin_Admin::instance();

		if ( ! isset( $_POST['noptin-action'] ) || 'save-automation-campaign' !== $_POST['noptin-action'] ) {
			return;
		}

		// Verify nonce.
		if ( empty( $_POST['noptin_campaign_nonce'] ) || ! wp_verify_nonce( $_POST['noptin_campaign_nonce'], 'noptin_campaign' ) ) {
			return $admin->show_error( __( 'Unable to save your campaign', 'newsletter-optin-box' ) );
		}

		// Prepare data.
		$data = stripslashes_deep( $_POST );
		$id   = (int) $data['id'];

		unset( $data['noptin_campaign_nonce'] );
		unset( $data['noptin-action'] );
		unset( $data['id'] );

		// Prepare post status.
		$status = get_post_status( $id );

		if ( ! empty( $data['draft'] ) ) {
			$status = 'draft';
		}

		if ( ! empty( $data['publish'] ) ) {
			$status = 'publish';
		}

		unset( $data['publish'] );
		unset( $data['draft'] );

		// Prepare post args.
		$post = array(
			'ID'           => $id,
			'post_status'  => $status,
			'post_type'    => 'noptin-campaign',
			'post_content' => $data['email_body'],
		);

		unset( $data['email_body'] );
		$post['meta_input'] = $data;

		$post = apply_filters( 'noptin_save_automation_campaign_details', $post, $data );

		$post = wp_update_post( $post, true );

		if ( is_wp_error( $post ) ) {
			$admin->show_error( $post->get_error_message() );
		} else {
			$admin->show_success( __( 'Your changes were saved successfully', 'newsletter-optin-box' ) );
		}

	}

	/**
	 *  Saves a newsletter campaign
	 */
	function maybe_save_campaign() {

		if ( wp_doing_ajax() ) {
			return;
		}

		$admin = Noptin_Admin::instance();

		if ( ! isset( $_GET['page'] ) || 'noptin-email-campaigns' !== $_GET['page'] ) {
			return;
		}

		if ( ! empty( $_GET['edited'] ) ) {
			$admin->show_success( __( 'Your campaign was saved.', 'newsletter-optin-box' ) );
		}

		if ( ! isset( $_POST['noptin-action'] ) || 'save-newsletter-campaign' !== $_POST['noptin-action'] ) {
			return;
		}

		// Verify nonce.
		if ( empty( $_POST['noptin_campaign_nonce'] ) || ! wp_verify_nonce( $_POST['noptin_campaign_nonce'], 'noptin_campaign' ) ) {
			return $admin->show_error( __( 'Unable to save your campaign', 'newsletter-optin-box' ) );
		}

		// Prepare data.
		$data = stripslashes_deep( $_POST );

		// Defaults.
		$id     = false;
		$status = 'draft';

		// Set post status.
		if ( ! empty( $data['id'] ) ) {
			$id     = (int) $data['id'];
			$status = ( 'draft' === get_post_status( $id ) ) ? 'draft' : 'publish';
		}

		if ( ! empty( $data['draft'] ) ) {
			$status = 'draft';
		}

		if ( ! empty( $data['publish'] ) ) {
			$status = 'publish';
		}

		// Prepare post args.
		$post = array(
			'post_status'   => $status,
			'post_type'     => 'noptin-campaign',
			'post_date_gmt' => current_time( 'mysql', true ),
			'post_date'     => current_time( 'mysql' ),
			'edit_date'     => 'true',
			'post_title'    => trim( $data['email_subject'] ),
			'post_content'  => $data['email_body'],
			'meta_input'    => array(
				'campaign_type'           => 'newsletter',
				'preview_text'            => sanitize_text_field( $data['preview_text'] ),
				'noptin_sends_after'      => (int) $data['noptin-email-schedule'],
				'noptin_sends_after_unit' => sanitize_text_field( $data['noptin-email-schedule-unit'] ),
			),
		);

		if ( 'publish' === $status & ! empty( $data['noptin-email-schedule'] ) ) {

			$count    = (int) $data['noptin-email-schedule'];
			$unit     = sanitize_text_field( $data['noptin-email-schedule-unit'] );
			$time     = current_time( 'mysql' );
			$time_gmt = current_time( 'mysql', true );

			$post['post_status']   = 'future';
			$post['post_date']     = gmdate( 'Y-m-d H:i:s', strtotime( "$time +$count $unit" ) );
			$post['post_date_gmt'] = gmdate( 'Y-m-d H:i:s', strtotime( "$time_gmt +$count $unit" ) );

		}

		$post = apply_filters( 'noptin_save_newsletter_campaign_details', $post, $data );

		if ( empty( $id ) ) {
			$post = wp_insert_post( $post, true );
		} else {
			$post['ID'] = $id;
			$post       = wp_update_post( $post, true );
		}

		if ( is_wp_error( $post ) ) {
			return $admin->show_error( $post->get_error_message() );
		}

		$url = get_noptin_newsletter_campaign_url( $post );
		wp_safe_redirect( add_query_arg( 'edited', '1', $url ) );
		exit;

	}

	/**
	 *  Deletes campaign stats.
	 *
	 * @param int $post_id the form whose stats should be delete.
	 */
	function maybe_delete_stats( $post_id ) {
		global $wpdb;

		$table = get_noptin_subscribers_meta_table_name();
		$wpdb->delete(
			$table,
			array(
				'meta_key' => "_campaign_$post_id",
			)
		);

	}

	/**
	 *  (Maybe) Sends a newsletter campaign.
	 *
	 * @param string  $new_status The new campaign status.
	 * @param string  $old_status The old campaign status.
	 * @param WP_Post $post The new campaign post object.
	 */
	function maybe_send_campaign( $new_status, $old_status, $post ) {

		// Maybe abort early.
		if ( 'publish' !== $new_status || 'publish' === $old_status ) {
			return;
		}

		// Ensure this is a newsletter campaign.
		if ( 'noptin-campaign' === $post->post_type && 'newsletter' === get_post_meta( $post->ID, 'campaign_type', true ) ) {
			$this->send_campaign( $post );
		}

	}

	/**
	 * Sends a newsletter campaign.
	 *
	 * @param WP_Post $post The new campaign post object.
	 */
	function send_campaign( $post ) {

		$noptin = noptin();

		$item = array(
			'campaign_id'       => $post->ID,
			'subscribers_query' => array(), // By default, send this to all active subscribers.
			'campaign_data'     => array(
				'campaign_id' => $post->ID,
				'template'    => locate_noptin_template( 'email-templates/paste.php' ),
			),
		);

		$noptin->bg_mailer->push_to_queue( $item );

		$noptin->bg_mailer->save()->dispatch();

	}

}
