<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PSUController extends Controller
{

    public function welcome()
    {
        return 'Welcome to PSU! Author: Rosario, Mark Rejie J. | Date: February 24, 2026';
    }

        public function vision()
    {
        return 'To be a leading insdustry-drive State University in the ASEAN region by 2030.';
    }

    public function mission()
    {
        return 'The Pangasinan State University shall provide a human-centric, resilient and sustainable academic environment<br>to produce dynamic, responsive and future-ready individuals<br>capable of meeting the requirements of the local and global communities and industries';
    }

    public function EOMSPolicy()
    {
        return 'The Pangasinan State University shall be recognized as an ASEAN <br> premier state university that provides quality education and satisfactory <br> service delivery through instruction, research, extension, and production.<br> We commit our expertise and resources to produce professionals <br> who meet the expectations of the industry and other interested parties <br> in the national and international community.<br> We shall continuously improve our operations in response to the changing <b environment and in support of the institution\'s strategic direction.';
    }

    public function student(string $name, string $course)
    {
        return "Student: {$name} | Course: {$course}";
    }

}
