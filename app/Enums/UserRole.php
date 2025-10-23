<?php

namespace App\Enums;

enum UserRole: string
{
    //
    case Admin = 'admin';
    case Staff = 'staff';
    case Student = 'student';
    case Teacher = 'teacher';
    case Guest = 'guest';
}
