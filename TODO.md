# TODO: Enhance Booking Workflow

## Steps to Complete:
- [x] Create migration to add borrow_date, return_date to bookings table and update status enum
- [ ] Update Booking model to include dates in fillable and casts
- [ ] Update BookingStoreRequest to validate borrow_date and return_date
- [ ] Update BookingController store method to save dates
- [ ] Update BookingController update method to handle revise status and notifications
- [ ] Update createBorrowFromBooking to use booking dates and add stock check
- [ ] Update routes to restrict borrow access to staff/admin
- [ ] Update booking views to include date inputs and display dates
- [ ] Create notification classes for approval and revision
- [ ] Run migration and test the workflow

## Notes:
- Users can view books and request specific borrow periods.
- Staff/admin can approve, reject, or revise requests.
- Upon approval, borrow records are created automatically with user-specified dates.
- Stock checks prevent over-borrowing.
- Notifications inform users of status changes.
