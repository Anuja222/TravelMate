import re

with open('app/views/transpoter/setting.view.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Remove notification settings block inside loadSavedSettings
content = re.sub(r'\s*// Notification settings\s*const savedNotifications =.*?\}\);\n?', '', content, flags=re.DOTALL)

with open('app/views/transpoter/setting.view.php', 'w', encoding='utf-8') as f:
    f.write(content)
