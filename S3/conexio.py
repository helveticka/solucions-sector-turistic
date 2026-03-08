import mysql.connector

def get_connection():
    db = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="PMS45609588"
    )
    return db