<?php
class StripeSubscriptions_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getSubscription($userId = null){
        if(is_null($userId)) {
            $userId = $_SESSION['uid'];
        }
        $subscription = $this->db->query("SELECT * FROM stripe_subscriptions WHERE user_id = '".$userId."'")->row();
        return $subscription;
    }

    public function cancelSubscription($subscription){
        $updateData = array(
            'ends_at' =>  (date('Y-m-d H:i:s ',($subscription->current_period_end)))
        );
        $this->db->where('user_id', $_SESSION['uid']);
        $this->db->update('stripe_subscriptions', $updateData);
    }

    public function resumeSubscription($subscription){
        $updateData = array(
            'ends_at' => null,
            'ends_date_subscription' => (date('Y-m-d H:i:s ',($subscription->current_period_end)))
        );
        $this->db->where('user_id', $_SESSION['uid']);
        $this->db->update('stripe_subscriptions', $updateData);
    }

    public function isSubscriptionActive($user)
    {
        return $this->db
            ->from('stripe_subscriptions')
            ->where("user_id = {$user->ID} AND (ends_at IS NULL OR DATE(ends_at) >= CURDATE())")
            ->count_all_results() > 0;
    }
}
