import re

with open('app/views/transpoter/setting.view.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

new_lines = []
skip = False
for line in lines:
    if 'const notificationForm' in line or 'const deleteAccountForm' in line or 'const deleteAccountModal' in line \
    or 'const confirmDeleteBtn' in line or 'const cancelDeleteBtn' in line or 'const deleteAccountBtn' in line \
    or 'const reasonRadios' in line or 'const feedbackGroup' in line:
        continue
    if 'notificationForm.addEventListener' in line or 'notificationForm?.addEventListener' in line:
        continue
    if 'deleteAccountForm.addEventListener' in line or 'deleteAccountForm?.addEventListener' in line:
        continue
    if 'deleteAccountBtn.addEventListener' in line or 'deleteAccountBtn?.addEventListener' in line:
        continue
    if 'cancelDeleteBtn.addEventListener' in line or 'cancelDeleteBtn?.addEventListener' in line:
        continue
    
    # functions to remove by name, we need a simple state machine:
    if line.startswith('  async function handleNotificationSubmit'):
        skip = True
    if line.startswith('  // Modal interactions'):
        skip = True    

    if skip and line.strip() == '}':
        # Check if next line is a new function, maybe wait one more? No, just end skip
        skip = False
        continue # skip the closing brace as well
        
    if not skip:
        new_lines.append(line)

with open('app/views/transpoter/setting.view.php', 'w', encoding='utf-8') as f:
    f.writelines(new_lines)

