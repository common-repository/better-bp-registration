<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sbsr_group_get_all_groups_arr()
{
    $groups = groups_get_groups();

    return $groups['groups'];
}

function sbsr_group_get_tree()
{
    $groups = sbsr_group_get_all_groups_arr();

    $root_groups = array();
    $sub_groups = array();

    foreach ($groups as $group) {
        if ($group->parent_id == 0) {
            $root_groups[] = $group;
        } else {
            $sub_groups[] = $group;
        }
    }

    return array($root_groups, $sub_groups);
}

function sbsr_get_sub_groups($sub_group_arr, $parent_id)
{
    $return = array();

    foreach ($sub_group_arr as $item) {
        if ($item->parent_id == $parent_id) {
            $return[] = $item;
        }
    }

    return $return;
}

function sbsr_groups_tree()
{
    function sbsr_group_has_child($groups_arr, $check_group)
    {
        $result = array();

        foreach ($groups_arr as $group) {
            if ($group->parent_id == $check_group->id) {
                $result[] = $group;
            }
        }

        if (!empty($result)) {
            return $result;
        }

        return false;
    }

    function sbsr_gardener($groups_arr, $group)
    {
        $sub_groups = sbsr_group_has_child($groups_arr, $group);

        if ($sub_groups) {
            foreach ($sub_groups as $sub_group) {
                $result_sub_groups[] = sbsr_gardener($groups_arr, $sub_group);
            }

            return array(
                'id' => $group->id,
                'slug' => $group->slug,
                'name' => $group->name,
                'parent_id' => $group->parent_id,
                'sub_groups' => $result_sub_groups,
            );
        } else {
            return array(
                'id' => $group->id,
                'slug' => $group->slug,
                'name' => $group->name,
                'parent_id' => $group->parent_id,
            );
        }
    }

    $groups_arr = sbsr_group_get_all_groups_arr();
    $result = array();

    foreach ($groups_arr as $group) {
        if ($group->parent_id == 0) {
            $result[] = sbsr_gardener($groups_arr, $group);
        }
    }

    return $result;
}
