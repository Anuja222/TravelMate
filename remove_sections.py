import re

with open('app/views/transpoter/setting.view.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Remove HTML sections
content = re.sub(
    r'<!-- Notification Settings -->.*?<!-- Delete Account Section -->.*?<button type="button" class="delete-btn" id="deleteAccountBtn">.*?Delete My Account.*?^\s*</button>\n\s*</form>\n\s*</section>',
    '',
    content,
    flags=re.DOTALL | re.MULTILINE
)

# Remove unused JS code
content = re.sub(r'const notificationForm = document\.getElementById\(''notificationForm''\);.*?\n', '', content)
content = re.sub(r'const deleteAccountBtn = document\.getElementById\(''deleteAccountBtn''\);.*?\n', '', content)

# Remove Notification event listener and function
content = re.sub(r'notificationForm\?\.addEventListener\(''submit'', handleNotificationSubmit\);\s*\n', '', content)
content = re.sub(r'deleteAccountBtn\?\.addEventListener\(''click'', showDeleteModal\);\s*\n', '', content)
content = re.sub(r'function handleNotificationSubmit\(e\) \{.*?\}\n', '', content, flags=re.DOTALL)
content = re.sub(r'function showDeleteModal\(\) \{.*?\}\n', '', content, flags=re.DOTALL)
content = re.sub(r'function handleAccountDeletion\(\) \{.*?\}\n', '', content, flags=re.DOTALL)
content = re.sub(r'function validateDeleteForm\(\) \{.*?\}\n', '', content, flags=re.DOTALL)
content = re.sub(r'function checkPasswordStrength\(password\) \{.*?(?=function formatFileSize)', '', content, flags=re.DOTALL)


with open('app/views/transpoter/setting.view.php', 'w', encoding='utf-8') as f:
    f.write(content)
