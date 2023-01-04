{* HEADER *}

<div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
</div>
<h2>
    {ts}General{/ts}
</h2>
<div id="help">{ts}This open source extension
        ( <a href="https://github.com/systopia/de.systopia.birthdays#readme">de.systopia.birthdays</a> )
        allows you to build a report on upcoming birthdays and provides an API for sending birthday
        greetings automatically via e-mail.{/ts}
    <br>
    {ts}Please note: With default settings no email will be sent after upgrading to this version.
        Mails only will be sent if triggering an APIv3 or APIv4 birthday action manually or using scheduled jobs.{/ts}
</div>
<br>
<h2>
    {ts}Updated extension?{/ts}
</h2>
<div id="help">
    {ts}If you updated from an older version chances are you need to run a migration in your{/ts}
    <a href="{crmURL p='civicrm/admin/extensions' q='reset=1'}">{ts}extension directory{/ts}</a>.
</div>
<br>
<hr>
<br>
<h2>
    {ts}Step 1: Provide an email message template{/ts}
</h2>
<div id="help">
    {ts}Those can be found in:{/ts}<br>
    {ts}Topbar --> "Mailings" -->{/ts} "<a href="{crmURL p='civicrm/admin/messageTemplates' q='reset=1'}">{ts}Message
            Templates{/ts}</a>"
    <br>
    <br>
    {ts}Tokens can be used here as expected.{/ts}
</div>
<br>
<h2>
    {ts}Step 2: Select a template{/ts}
</h2>
<div id="help">
    {ts}Please select a previously created or edited template for birthday greeting emails.<br>
    Make sure to reload this page if your template is not listed here. Templates need to be activated before.{/ts}<br>
</div>
<div class="crm-section">
    <div class="label">{$form.message_template_id.label}</div>
    <div class="content">{$form.message_template_id.html}</div>
    <div class="clear"></div>
</div>
<div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
<br>
<h2>
    {ts}Step 3: Sender email preparation{/ts}
</h2>
<div id="help">{ts}This step is optional if you are happy with the provided options in the upcomming section.{/ts}
    <br>
    <br>
    {ts}Those email addressed can be added using the topbar: "Administer" --> "Administration Console" -->{/ts}
    "<a href="{crmURL p='civicrm/admin/messageTemplates' q='reset=1'}">{ts}From Email Addresses{/ts}</a>"<br><br>
    {ts}Please talk to your sysadmin if there are additional steps needed in order to add emails
    there to avoid problems.{/ts}<br>
    {ts}Check if this is a noreply address or if you would like to monitor this email inbox.{/ts}
</div>
<br>
<h2>
    {ts}Step 4: Sender email selection{/ts}
</h2>
<div id="help">{ts}Please select an outgoing email address for birthday greeting emails.{/ts}<br>
    {ts}If this is empty please set up an email address as described in the previous section{/ts}
    <br>
</div>
<div class="crm-section">
    <div class="label">{$form.birthday_sender_email_address_id.label}</div>
    <div class="content">{$form.birthday_sender_email_address_id.html}</div>
    <div class="clear"></div>
</div>
<div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
<br>
<h2>
    {ts}Step 5: Make sure to understand filter criteria{/ts}
</h2>
<div id="help"><b>{ts}The contact filter currently filters contacts with:{/ts}</b><br>
    <ul> <!-- Please sync sql query documentation in CRM/Birthdays/BirthdayContacts.php
            whenever you add or change things here-->
        <li>{ts}contacts which are part of the programmatically prepared birthday group{/ts}</li>
        <li>{ts}contacts with a birthday which is equal with the date of execution
                (unless debug mode is set - see below)
            {/ts}</li>
        <li><code>contact_type</code> = 'Individual'</li>
        <li><code>opt_out</code> = 0</li>
        <li><code>do_not_email</code> = 0</li>
        <li>{ts}Contact has a primary email address set{/ts}</li>
    </ul>
</div>
<br>
<h2>
    {ts}Step 6: Add some test contacts{/ts}
</h2>
<div id="help">
    {ts}Keep section "filter criteria" above in mind and add some test contacts to the birthday greeting group
    and set their birthday to today.{/ts}<br>
    {ts}Use the topbar to navigate to: "Contacts" -->{/ts}
    "<a href="{crmURL p='civicrm/group' q='reset=1'}">{ts}Manage Groups{/ts}</a>"<br>
    {ts}This group was programatically created by this extension by installation.{/ts}
</div>
<br>
<h2>
    {ts}Step 7: Automation using scheduled jobs{/ts}
</h2>
<div id="help">
    {ts}Use the topbar to navigate to: "Administer" --> "Administration Console" -->{/ts} "
    <a href="{crmURL p='/civicrm/admin/job' q='reset=1'}">{ts}Scheduled Jobs{/ts}</a>".<br>
    {ts}Click button: "Add New Scheduled Job"{/ts}<br>
    {ts}Name for example: "Daily email birthday greetings"{/ts}<br>
    {ts}Run frequency: "Daily"{/ts}<br>
    {ts}Select "Birthdays" using the dropdown list{/ts}<br>
    {ts}Type "sendgreetings" (lowercase) into the textfield behind the dropdown list{/ts}<br>
    {ts}Leave "Command parameters" empty{/ts}<br>
    {ts}Scheduled Run Date: Can be left empty while testing.{/ts}<br>
    {ts}Is this Scheduled Job active?: Can be left de-activated while testing.{/ts}<br>
    {ts}Click: "Save"{/ts}<br>
    {ts}<b>Warning:</b> Using scheduled jobs setting <code>runInNonProductionEnvironment=TRUE</code>
        in a development
        <a href="{crmURL p='/civicrm/admin/setting/smtp' q='reset=1'}">{ts}emails set to
            "redirected to database"{/ts}</a> will <u>send an actual email</u>
        skipping db redirection!{/ts}
</div>
<br>
<h2>
    {ts}Step 8: Testing{/ts}
</h2>
<div id="help">
    {ts}Check if you want to use the special debug mode described in the next chapter to avoid traces
    in your system{/ts}<br>
    {ts}Again use the topbar to navigate to: "Administer" --> "Administration Console" -->{/ts} "
    <a href="{crmURL p='/civicrm/admin/job' q='reset=1'}">{ts}Scheduled Jobs{/ts}</a>".<br>
    {ts}Find the previous created birthday job in this list{/ts}<br>
    {ts}Click: "more"{/ts}<br>
    {ts}Click: "Execute Now" <b>(Warning: <u>Actual emails</u> will be sent to the contacts email addresses
    if everything works as expected!)</b>{/ts}<br>
    {ts}Your previoulsy selected contacts should now receive an email with birthday greetings{/ts}
    {ts}Make sure to check what type of activity description had been added to your selected contacts.{/ts}
    <br>
    {ts}If you are familiar with executing API commands you also can use{/ts}
    <a href="{crmURL p='/civicrm/api4#/explorer/Birthdays/sendGreetings' q=''}">APIv4</a>{ts}
    to trigger sending of mails.{/ts}
</div>
<br>


<br>
<h2>
    {ts}Test / debug mode (!! Sysadmins only !!){/ts}
</h2>
<div id="help"><b>{ts}A debug mode can be set programatically:{/ts}</b><br>
    {ts}Navigate to:{/ts} <code>/de.systopia.birthdays/CRM/Birthdays/BirthdayContacts.php</code><br>
    {ts}Set:<code>$is_debug_email = 'all-birthday-mails-go-to@this-domain.com';</code>using an code editor.{/ts}
    <p>{ts}This changes the extensions behavior to:{/ts}</p>
    <ul> <!-- Please sync this documentation with mentioned php class path-->
        <li>{ts}Adding "successful" or "failed" activities will be suppressed{/ts}</li>
        <li>{ts}All emails will be redirected to this email set above{/ts}</li>
        <li>{ts}A filter is de-activated which selects the first 10 contacts/mails where a birth date is set{/ts}</li>
    </ul>
    <br>
    {ts}Set an empty string <code>$is_debug_email = '';</code> to de-activate debug mode.{/ts}
</div>
<br>
<h2>
    {ts}Final activation{/ts}
</h2>
<div id="help">
    {ts}Again use the topbar to navigate to: "Administer" --> "Administration Console" -->{/ts} "
    <a href="{crmURL p='/civicrm/admin/job' q='reset=1'}">{ts}Scheduled Jobs{/ts}</a>".<br>
    {ts}Again find the previous created birthday job in this list{/ts}<br>
    {ts}Again click: "more"{/ts}<br>
    {ts}Is this Scheduled Job active?: Set a first execution date and time.{/ts}<br>
    {ts}Click: "Save"{/ts}<br>
    {ts}Again find the previous created birthday job in this list{/ts}<br>
    {ts}Again click: "more"{/ts}<br>
    {ts}Click: "Enable". This enables the daily sending of birthday mails for your instance.{/ts}<br>
</div>
<br>
<hr>
<br>
<h2>
    {ts}Extension development{/ts}
</h2>
<div id="help">
    {ts}If you bring development skills feel free to
        <a href="https://github.com/systopia/de.systopia.birthdays/pulls">leave a pull request</a>
        and extend this open source extension or contact
        <a href="mailto:info@systopia.de">info@systopia.de</a>
        for contract work.{/ts}
</div>
<br>
{* FOOTER *}
<div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
