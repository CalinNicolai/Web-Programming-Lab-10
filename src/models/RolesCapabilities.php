<?php

namespace App\models;

class RolesCapabilities
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var Role
     */
    public Role $role;

    /**
     * @var Capability
     */
    public Capability $capability;

    /**
     * RolesCapabilities constructor.
     * @param $id
     * @param Role $role
     * @param Capability $capability
     */
    public function __construct($id, Role $role, Capability $capability)
    {
        $this->id = $id;
        $this->role = $role;
        $this->capability = $capability;
    }

    /**
     * Get the SQL query for creating the roles_capabilities table.
     * @return string
     */
    public static function getCreateTableQuery(): string{
        return "CREATE TABLE IF NOT EXISTS roles_capabilities (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            role_id INT NOT NULL,
            capability_id INT NOT NULL,
            FOREIGN KEY (role_id) REFERENCES roles(id),
            FOREIGN KEY (capability_id) REFERENCES capabilities(id)
        );";
    }
}
