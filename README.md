## LWP Contact Form

## Description

This is a simple demo plugin to create a contact form in WordPress. The plugin adds a shortcode `[lwp_contact_form]` to display the form on the front end.
The form has the following fields:
- First Name
- Last Name
- Email
- Message

### How to use the plugin?

If you are familiar with git, you can clone the repository and checkout the branches to see the step by step development of the plugin.

If you are new to git, you can download the zip file of the repository and install the plugin in your WordPress site.

> Note: This is a demo plugin and should not be used in a production site without proper testing and validation.

### Learning Objectives

- Create a simple contact form in WordPress.
- Use the shortcode API to display the form on the front end.
- Handle form submission and display success message.
- Use nonce field to prevent CSRF attacks. ( Branch `step-8` )
- Sanitize and validate form data. ( Branch `step-9` )
- Save form entry to the database. ( Branch `step-10` )
- Display form entries in the admin. ( Branch `step-10` )

### Resources

[Let’s code: creating your first WordPress plugin](https://wordpress.tv/2022/07/22/lets-code-creating-your-first-wordpress-plugin/)

[Plugin API Hooks](https://wordpress.tv/2024/05/24/plugin-api-hooks/)

[WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)

### WordPress Concepts Used

- Shortcode API
- Nonce field
- Sanitization
- Custom Post Type
- Custom Meta fields
- Admin Columns

### Extend the plugin

- Add more fields to the form.
- Multistep form.
- Add file upload field.
- Use PHP Classes to organize the code.
- Add email notification to the admin.

### Branches
- `main` - Stable version
- `step-1` - Adds readme file and plugin header for the plugin.
- `step-2` - Adds shortcode and placeholder for the form.
- `step-3` - Adds the form fields HTML.
- `step-4` - Adds the CSS for the form.
- `step-5` - Adds the submission handler, print the form data for testing.
- `step-6` - Adds the success message.