<?php

namespace Universe\Model;

interface EntityCommandInterface
{
    /**
     * Persist a new Entity in the system.
     *
     * @param Entity $entity The entity to insert; may or may not have an identifier.
     * @return Entity The inserted entity, with identifier.
     */
    public function insertEntity(Entity $entity);

    /**
     * Update an existing Entity in the system.
     *
     * @param Entity $entity The entity to update; must have an identifier.
     * @return Entity The updated entity.
     */
    public function updateEntity(Entity $entity);

    /**
     * Delete a entity from the system.
     *
     * @param Entity $entity The entity to delete.
     * @return bool
     */
    public function deleteEntity(Entity $entity);
}
