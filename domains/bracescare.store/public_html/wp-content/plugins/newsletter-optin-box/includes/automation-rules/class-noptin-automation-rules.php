<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' )  ) {
	die;
}

/**
 * The automation rules class.
 *
 * @since       1.2.8
 */
class Noptin_Automation_Rules {

    /**
     * @var Noptin_Abstract_Action[] $actions All registered actions.
     */
    private $actions = array();

    /**
     * @var Noptin_Abstract_Trigger[] $triggers All registered triggers.
     */
    private $triggers = array();

    /**
     * Constructor.
     *
     * @since 1.2.8
     * @return string
     */
    public function __construct() {

        // Register core actions
        $this->add_action( new Noptin_Custom_Field_Action() );
        if ( class_exists( 'WooCommerce' ) ) {
            $this->add_action( new Noptin_WooCommerce_Coupon_Action() );
        }

        // Register core triggers
        $this->add_trigger( new Noptin_New_Subscriber_Trigger() );
        $this->add_trigger( new Noptin_Open_Email_Trigger() );
        $this->add_trigger( new Noptin_Link_Click_Trigger() );
        if ( class_exists( 'WooCommerce' ) ) {
            $this->add_trigger( new Noptin_WooCommerce_New_Order_Trigger() );
            $this->add_trigger( new Noptin_WooCommerce_Product_Purchase_Trigger() );
        }

        do_action( 'noptin_automation_rules_load', $this );
    }

    /**
     * Registers an action.
     *
     * @since 1.2.8
     * @param Noptin_Abstract_Action $action An ancestor of Noptin_Abstract_Action
     */
    public function add_action( $action ) {
        $this->actions[ $action->get_id() ] = $action;
    }

    /**
     * Checks if there is an action with that id.
     *
     * @since 1.2.8
     * @param string $action_id The action's uniques id.
     * @return bool whether or not the action exists.
     */
    public function has_action( $action_id ) {
        return is_scalar( $action_id ) && ! empty( $this->actions[ $action_id ] );
    }

    /**
     * Retrieves a registered action.
     *
     * @since 1.2.8
     * @param string $action_id The action's uniques id.
     * @return Noptin_Abstract_Action|null
     */
    public function get_action( $action_id ) {
        return empty( $this->actions[ $action_id ] ) ? null : $this->actions[ $action_id ];
    }

    /**
     * Returns all registered actions.
     *
     * @since 1.2.8
     * @return Noptin_Abstract_Action[]
     */
    public function get_actions() {
        return $this->actions;
    }

    /**
     * Registers a trigger.
     *
     * @since 1.2.8
     * @param Noptin_Abstract_Trigger $trigger An ancestor of Noptin_Abstract_Trigger
     */
    public function add_trigger( $trigger ) {
        $this->triggers[ $trigger->get_id() ] = $trigger;
    }

    /**
     * Retrieves a registered trigger.
     *
     * @since 1.2.8
     * @param string $trigger_id The trigger's uniques id.
     * @return Noptin_Abstract_Trigger|null
     */
    public function get_trigger( $trigger_id ) {
        return empty( $this->triggers[ $trigger_id ] ) ? null : $this->triggers[ $trigger_id ];
    }

    /**
     * Returns all registered triggers.
     *
     * @since 1.2.8
     * @return Noptin_Abstract_Trigger[]
     */
    public function get_triggers() {
        return $this->triggers;
    }

    /**
     * Checks if there is a trigger with that id.
     *
     * @since 1.2.8
     * @param string $trigger_id The trigger's unique id.
     * @return bool whether or not the trigger exists.
     */
    public function has_trigger( $trigger_id ) {
        return is_scalar( $trigger_id ) && ! empty( $this->triggers[ $trigger_id ] );
    }

    /**
     * Prepares a rule.
     *
     * @since 1.2.8
     * @param stdClass|int|Noptin_Automation_Rule $rule The (maybe) raw rule.
     * @return Noptin_Automation_Rule The prepared rule.
     */
    public function prepare_rule( $rule ) {
        return new Noptin_Automation_Rule( $rule );
    }

    /**
     * Creates a new rule.
     *
     * @since 1.2.8
     * @param array $rule The rule arguments.
     * @return bool|Noptin_Automation_Rule
     */
    public function create_rule( $rule ) {
        global $wpdb;

        // Ensure that we have an array.
        if ( ! is_array( $rule ) ) {
            $rule = array();
        }

        // Our database fields with defaults set.
        $fields = array(
            'action_id'       => '',
            'action_settings' => array(),
            'trigger_id'       => '',
            'trigger_settings' => array(),
            'status'           => 1, // Active.
            'times_run'        => 0,
            'created_at'       => current_time( 'mysql' ),
            'updated_at'       => current_time( 'mysql' ),
        );

        foreach ( array_keys( $fields ) as $key ) {
            
            if ( isset( $rule[ $key ] ) ) {
                $fields[ $key ] = $rule[ $key ];
            }

            $fields[ $key ] = maybe_serialize( $fields[ $key ] );

        }

        if ( ! $wpdb->insert( $this->get_table(), $fields, '%s' ) ) {
            return false;
        }

        return new Noptin_Automation_Rule( $wpdb->insert_id );

    }

    /**
     * Updates a rule.
     *
     * @since 1.2.8
     * @param int|Noptin_Automation_Rule $rule The rule to update
     * @param array $to_update The new $arguments.
     * @return bool|Noptin_Automation_Rule
     */
    public function update_rule( $rule, $to_update ) {
        global $wpdb;

        $rule = new Noptin_Automation_Rule( $rule );

        // Does the rule exist?
        if ( ! $rule->exists() ) {
            return false;
        }

        // Our database fields with defaults set.
        $fields = array(
            'action_id'        => $rule->action_id,
            'action_settings'  => $rule->action_settings,
            'trigger_id'       => $rule->trigger_id,
            'trigger_settings' => $rule->trigger_settings,
            'status'           => $rule->status,
            'times_run'        => $rule->times_run,
            'created_at'       => $rule->created_at,
        );

        foreach ( array_keys( $fields ) as $key ) {
            
            if ( isset( $to_update[ $key ] ) ) {
                $fields[ $key ] = $to_update[ $key ];
                $fields[ $key ] = maybe_serialize( $fields[ $key ] );
            } else {
                unset( $fields[ $key ] );
            }

        }

        $fields['updated_at'] = current_time( 'mysql' );

        if ( ! $wpdb->update( $this->get_table(), $fields, array( 'id' => $rule->id ) ) ) {
            return false;
        }

        wp_cache_delete( $rule->id, 'noptin_automation_rules' );
        return new Noptin_Automation_Rule( $rule->id );

    }

    /**
     * Returns the rule's database table.
     *
     * @since 1.2.8
     * @return string
     */
    public function get_table() {
        global $wpdb;
        return $wpdb->prefix . 'noptin_automation_rules';
    }

}
