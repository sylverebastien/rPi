#!/usr/bin/env python
#
# Very simple Python script to dump all emails in an IMAP folder to files.
# This code is released into the public domain.
#
# RKI Nov 2013
#
import sys
import imaplib
import getpass
import email
import email.header


IMAP_SERVER = 'imap.gmail.com'
EMAIL_ACCOUNT = "example@gmail.com"
EMAIL_FOLDER = "INBOX"
OUTPUT_DIRECTORY = 'folder'

PASSWORD = getpass.getpass()


def process_mailbox(M):
    """
    Dump all emails in the folder to files in output directory.
    """
    rv, data = M.search(None, '(UNSEEN)')
    if data == ['']:
        print "No messages found!"
        return

    for num in data[0].split():
        rv, data = M.fetch(num, '(RFC822)')
        print "Writing message :", num
        msg = email.message_from_string(data[0][1])
        rv, data = M.store(num,'+FLAGS','\\Seen')
        if msg.is_multipart():
            for part in msg.walk():
                ctype = part.get_content_type()
                cdispo = str(part.get('Content-Disposition'))
                if ctype == 'text/plain' and 'attachment' not in cdispo:
                    body = part.get_payload(decode=True)
                    break
        else:
            body = msg.get_payload(decode=True)
        f = open('%s/%s.eml' %(OUTPUT_DIRECTORY, num), 'wb')
        f.write(unicode(email.header.decode_header(msg['From'])[0][0])+'\n')
        f.write(msg['Date']+'\n')
        f.write(unicode(email.header.decode_header(msg['Subject'])[0][0])+'\n')
        f.write(body)
        f.close()


def main():
    M = imaplib.IMAP4_SSL(IMAP_SERVER)
    M.login(EMAIL_ACCOUNT, PASSWORD)
    rv, data = M.select(EMAIL_FOLDER)
    if rv == 'OK':
        print "Processing mailbox: ", EMAIL_FOLDER
        process_mailbox(M)
        M.close()
    else:
        print "ERROR: Unable to open mailbox ", rv
    M.logout()

if __name__ == "__main__":
    main()
