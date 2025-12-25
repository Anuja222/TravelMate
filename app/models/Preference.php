<?php
namespace App\Models;

class Preference {
    public $userId;
    public $environments;
    public $activities;

    public function __construct($userId, $environments, $activities) {
        $this->userId = $userId;
        $this->environments = $environments;
        $this->activities = $activities;
    }

    public function savePreferences($conn) {
        try {
            // Start transaction
            $conn->beginTransaction();

            // Insert environments
            foreach ($this->environments as $environment) {
                $sql = "INSERT INTO user_environments (user_id, environment_name) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$this->userId, $environment]);
            }

            // Insert activities
            foreach ($this->activities as $activity) {
                $sql = "INSERT INTO user_activities (user_id, activity_name) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$this->userId, $activity]);
            }

            // Commit transaction
            $conn->commit();
            return true;
        } catch (\Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
}