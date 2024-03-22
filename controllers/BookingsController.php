<?php

class BookingsController
{
  public function get_all_bookings()
  {
    echo 'All bookings';
  }

  public function get_booking($vars)
  {
    echo 'Booking with id: ' . $vars['id'];
  }
}
