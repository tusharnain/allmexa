<?php
namespace App\Enums;

final class NomineeRelation
{
    //! if making changes in RELATIONS constant, then you have to change the enum options for database too
    const RELATIONS = [
        'Self', 'Acquaintance', 'Aunt', 'Best Friend', 'Boss', 'Brother', 'Business Partner', 'Cousin', 'Classmate', 'Client', 'Close Friend', 'Club Member', 'Coach', 'Co-founder', 'Colleague', 'Customer', 'Daughter', 'Doctor', 'Employee', 'Ex-Partner', 'Fiancé', 'Fiancée', 'Fitness Buddy', 'Friend', 'Gym Partner', 'Godchild', 'Godparent', 'Grandfather', 'Grandmother', 'Investor', 'Landlord', 'Lawyer', 'Mentee', 'Mentor', 'Mother', 'Neighbor', 'Nephew', 'Niece', 'Online Friend', 'Parent', 'Partner', 'Patient', 'Pen Pal', 'Relative', 'Roommate', 'School Friend', 'Schoolmate', 'Sister', 'Son', 'Spouse', 'Stranger', 'Student', 'Study Partner', 'Subordinate', 'Supervisor', 'Support Group Member', 'Teacher', 'Teammate', 'Tenant', 'Therapist', 'Travel Buddy', 'Uncle', 'Vendor', 'Work Friend', 'Writer', 'Other',
    ];


    public static function getRelationString(): string
    {
        return implode(',', self::RELATIONS);
    }
    public static function getRelationIndexes(): array
    {
        return range(0, count(self::RELATIONS));
    }
}
