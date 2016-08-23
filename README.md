## Agile Learning Center Post Type

This plugin creates an ALC Post Type for tracking and displaying ALCs

## TODO

- Auto geocode incoming forms?
 - Move 4253cffadffbfd57f075962d5aeec3d16428baa2 onto the geocode branch
- Add logo metabox
 - Resize images into multiple sizes
- Add example of the map box
- Custom admin list view
 - Filter by upcoming renewal
- Associate ALC Post with Site and display edit page on site's admin settings

## How to: Update gravity form

Make changes to the gravity form on a dev server, test them then export the forms settings.

Replace `gravity-form-template.json` and commit.

Import new form to live website.

## Structure

- alc_profile
 - alc_profile_address
 - alc_profile_website
 - alc_profile_facebook
 - alc_profile_twitter
- alc_holders
 - alc_holders_primary_name
 - alc_holders_primary_email
 - alc_holders_primary_phone
 - alc_holders_other_contacts
- alc_org
 - alc_org_age_range
 - alc_org_open_hours
 - alc_org_open_days
 - alc_org_enrollment_type
- alc_map_info
 - alc_map_info_on_map
 - alc_map_info_geocode
 - alc_map_info_name
 - alc_map_info_description
 - alc_map_info_cta_label
 - alc_map_info_cta
 - alc_map_info_contact_name
 - alc_map_info_contact_email
 - alc_map_info_contact_phone
- alc_membership
 - alc_membership_active
 - alc_membership_paid
 - alc_membership_enrollments
 - alc_membership_dues_usd
 - alc_membership_last_payment_date
 - alc_membership_harbor_pilot
 - alc_membership_join_date
- alc_exchange
 - alc_exchange_students
 - alc_exchange_alf
 - alc_exchange_description

Standing Taxonomy
alc-standing
- Steps in ALC Health

Type Taxonomy
alc-type
- Startup
- Using Tools
- Converting (from another model)