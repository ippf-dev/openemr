update layout_options SET uor=0 where `field_id` in ('education_level','religion','nationality');

update form_encounter set facility = (select name from facility where facility.id=form_encounter.facility_id) where facility is null;
