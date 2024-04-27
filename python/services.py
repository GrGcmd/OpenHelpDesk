import mysql.connector
from datetime import datetime, timedelta
import time
import threading
import logging
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from logging.handlers import RotatingFileHandler

logging.basicConfig(
    handlers=[RotatingFileHandler(filename="helpdesk.log", maxBytes=10 * 1024 * 1024, backupCount=10)],
    level=logging.INFO, format='[%(asctime)s] %(levelname)s: %(message)s', datefmt='%d-%m-%Y %I:%M:%S %p')

hostname = "localhost"
user_name = "ithelpuser"
user_password = "*******"
db_name = "ithelp"


class Error(Exception):
    """Base class for exceptions in this module."""
    pass



def create_connection(host_name, user_name, user_password, db_name):
    connection = None
    try:
        connection = mysql.connector.connect(
            host=host_name,
            user=user_name,
            passwd=user_password,
            database=db_name
        )

    except Error as e:
        print("The error '{e}' occurred")

    return connection


def execute_read_query(query):
    connection = create_connection(hostname, user_name, user_password, db_name)
    cursor = connection.cursor()
    result = None
    try:
        cursor.execute(query)
        result = cursor.fetchall()

        return result
    except Error as e:
        print("The error '{e}' occurred")
    finally:
        connection.close()


def execute_query(query):
    connection = create_connection(hostname, user_name, user_password, db_name)
    cursor = connection.cursor()
    try:
        cursor.execute(query)
        connection.commit()

    except Error as e:
        print("The error '{e}' occurred")
    finally:
        connection.close()


deletechars_picture = '«<>!,/:*<>()?|+=["];&^%$#@№'
deletechars_text = '«<>!,/:*<>()?|+=["];&^%$#@№'


def remove(value, deletechars):
    for c in deletechars:
        value = value.replace(c, '')
    return value

def send_mail_message(smtp_server,smtp_port,smtp_ssl,smtp_login,smtp_password,smtp_from_email,to_email,theme,message):
    try:
        mail_status = "SELECT status_int FROM bid_system WHERE id=14"
        mail_status_id = execute_read_query(mail_status)
        if mail_status_id[0][0] == 0:
            return
        if to_email != None and to_email != '':
            subject = '📧 HelpDesk: ' + theme[:40] + '...'

            smtp_server = smtplib.SMTP(smtp_server, smtp_port)
            smtp_server.starttls()
            smtp_server.login(smtp_login, smtp_password)

            msg = MIMEMultipart()


            msg["From"] = smtp_from_email
            msg["To"] = to_email
            msg["Subject"] = subject


            msg.attach(MIMEText(message, "plain"))


            smtp_server.sendmail(smtp_from_email, to_email, msg.as_string())


            smtp_server.quit()

            time.sleep(0.5)
        else:
            logging.info('Нет e-mail адреса для сообщения:\n'+str(message))
    except Exception as e:
        logging.error("Ошибка отправки e-mail: " + str(e))
        time.sleep(5)

def check_alarms_and_send_mail():
    logging.info('Запуск службы check_alarms_and_send_mails')
    while True:
        try:

            mail_cred = "SELECT id,status_int,status_text FROM bid_system WHERE id in (8,9,10,11,12,13)"
            mail_creds_id = execute_read_query(mail_cred)
            for index_str in mail_creds_id:
                if index_str[0] == 8:
                    smtp_server = index_str[2]
                elif index_str[0] == 9:
                    smtp_port = index_str[2]
                elif index_str[0] == 10:
                    smtp_ssl = index_str[1]
                elif index_str[0] == 11:
                    smtp_login = index_str[2]
                elif index_str[0] == 12:
                    smtp_password = index_str[2]
                elif index_str[0] == 13:
                    smtp_from_email = index_str[2]


            bid_alarm_count = "SELECT count(id) FROM bid WHERE alarm_create=1"
            bid_alarm_count_id = execute_read_query(bid_alarm_count)
            if bid_alarm_count_id[0][0]>=1:


                system_domain = "SELECT status_text FROM bid_system WHERE id=15"
                system_domain_id = execute_read_query(system_domain)

                bid_alarm_update_detail = "SELECT id,theme,owner,contractor FROM bid WHERE alarm_create=1"
                bid_alarm_update_detail_id = execute_read_query(bid_alarm_update_detail)
                for index_str in bid_alarm_update_detail_id:

                    email_owner = "SELECT email FROM users WHERE id='" + str(index_str[2]) + "'"
                    email_owner_id = execute_read_query(email_owner)

                    for index_mail_id_owner in email_owner_id:
                        message_update = 'Вами создана заявка №'+str(index_str[0]) + ('\n\n'
                        'Постоянная ссылка: \nhttp://' + str(system_domain_id[0][0]) + '/help/status.php?page=1&bid=' + str(index_str[0]) + '&details') + ('\n\n'
                        'Письмо сгенерированно автоматически. Не отвечайте на него.')
                        send_mail_message(smtp_server,smtp_port,smtp_ssl,smtp_login,smtp_password,smtp_from_email,index_mail_id_owner[0],str(index_str[1]),message_update)


                    update_dgu_alarm_time = "UPDATE bid SET alarm_create=0 WHERE id='" + str(index_str[0]) + "' LIMIT 1"
                    execute_query(update_dgu_alarm_time)


            bid_alarm_count = "SELECT count(id) FROM bid WHERE alarm_update=1"
            bid_alarm_count_id = execute_read_query(bid_alarm_count)
            if bid_alarm_count_id[0][0]>=1:


                system_domain = "SELECT status_text FROM bid_system WHERE id=15"
                system_domain_id = execute_read_query(system_domain)

                bid_alarm_update_detail = "SELECT id,theme,owner,contractor FROM bid WHERE alarm_update=1"
                bid_alarm_update_detail_id = execute_read_query(bid_alarm_update_detail)
                for index_str in bid_alarm_update_detail_id:

                    email_owner = "SELECT email FROM users WHERE id='" + str(index_str[2]) + "'"
                    email_owner_id = execute_read_query(email_owner)

                    for index_mail_id_owner in email_owner_id:
                        message_update = 'Обновление информации в заявке №'+str(index_str[0]) + ('\n\n'
                        'Подробнее: \nhttp://' + str(system_domain_id[0][0]) + '/help/status.php?page=1&bid=' + str(index_str[0]) + '&details') + ('\n\n'
                        'Письмо сгенерированно автоматически. Не отвечайте на него.')
                        send_mail_message(smtp_server,smtp_port,smtp_ssl,smtp_login,smtp_password,smtp_from_email,index_mail_id_owner[0],str(index_str[1]),message_update)

                    email_contractor = "SELECT email FROM users WHERE id='" + str(index_str[3]) + "'"
                    email_contractor_id = execute_read_query(email_contractor)
                    for index_mail_id_creator in email_contractor_id:
                        message_update = 'Обновление информации в назначенной вам на исполнение заявке №' + str(index_str[0]) + ('\n\n'
                        'Подробнее: \nhttp://' + str(system_domain_id[0][0]) + '/help/admin/status.php?page=1&bid=' + str(index_str[0]) + '&details')+ ('\n\n'
                        'Письмо сгенерированно автоматически. Не отвечайте на него.')
                        send_mail_message(smtp_server,smtp_port,smtp_ssl,smtp_login,smtp_password,smtp_from_email,index_mail_id_creator[0], str(index_str[1]), message_update)


                    update_dgu_alarm_time = "UPDATE bid SET alarm_update=0 WHERE id='" + str(index_str[0]) + "' LIMIT 1"
                    execute_query(update_dgu_alarm_time)

        except Exception as e:
            logging.error("Ошибка в работе функции check_alarms_and_send_mail: " + str(e))
            time.sleep(60)
        time.sleep(15)

def servicing():
    logging.info('Запуск службы services')
    while True:
        try:
            system_timeout = "SELECT status_int FROM bid_system WHERE id=16"
            system_timeout_id = execute_read_query(system_timeout)

            bids_status_done = "SELECT id FROM bid WHERE status=4"
            bids_status_done_id = execute_read_query(bids_status_done)
            for index_status_done in bids_status_done_id:
                bids_status_done_history = "SELECT max(date) FROM bid_history WHERE id_bid='" + str(index_status_done[0]) + "'"
                bids_status_done_history_id = execute_read_query(bids_status_done_history)
                if bids_status_done_history_id[0][0] == None:
                    history_max_date=0
                else:
                    history_max_date = bids_status_done_history_id[0][0]
                if (int(time.time())-int(history_max_date))>int(system_timeout_id[0][0]*60*60):
                    update_dgu_alarm_time = "UPDATE bid SET status=5 WHERE id='" + str(index_status_done[0]) + "' LIMIT 1"
                    execute_query(update_dgu_alarm_time)

                    bids_status_history = "SELECT count(id) FROM bid_history LIMIT 1"
                    bids_status_history_id = execute_read_query(bids_status_history)

                    insert_camera_information = "INSERT INTO bid_history (id,id_bid,status,date,user) VALUES ('" + str(bids_status_history_id[0][0]+1) + "','" + str(index_status_done[0]) + "','" + str(5) + "','" + str(int(time.time())) + "','" + str(0) + "')"
                    execute_query(insert_camera_information)

        except Exception as e:
            logging.error("Ошибка в работе функции services: " + str(e))
            time.sleep(60)
        time.sleep(600)




if __name__ == '__main__':
    logging.info('Запуск сервисной службы')
    service_1 = threading.Thread(target=check_alarms_and_send_mail)
    service_1.start()
    time.sleep(1)
    service_2 = threading.Thread(target=servicing)
    service_2.start()

    while True:
        time.sleep(300)
