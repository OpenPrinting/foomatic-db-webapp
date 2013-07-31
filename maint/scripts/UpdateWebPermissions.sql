INSERT IGNORE INTO `web_permissions` (
`privName` ,
`title`
)
VALUES (
'printer_upload', 'Allow printer uploading'
);

INSERT IGNORE INTO `web_permissions` (
`privName` ,
`title`
)
VALUES (
'printer_noqueue', 'Bypass printer upload moderation queue'
);