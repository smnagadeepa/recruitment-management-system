from flask import Flask, render_template, request, redirect, url_for, session
from flask_cors import CORS
from flask_mysqldb import MySQL
from pytz import timezone
from datetime import datetime
from dateutil import parser 
import pytz
import json
from json import JSONEncoder
from werkzeug.utils import secure_filename
app = Flask(__name__)
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'drs'
CORS(app) 
 
app.secret_key = 'your secret key'
mysql = MySQL(app)
now_utc = datetime.now(timezone('UTC'))
now_asia = now_utc.astimezone(timezone('Asia/Kolkata'))

@app.route('/3',methods=['GET', 'POST'])
def userreg():
    return render_template('3.html')

import hashlib
@app.route('/register', methods=['GET','POST'])
def register():
    name=request.form.get('name')
    email=request.form.get('email')
    password=request.form.get('password')
    
    cursor=mysql.connection.cursor()
    #pwd = hashlib.md5(pwd.encode('utf-8')).digest()
    cursor.execute(''' INSERT INTO user(name,email,password) VALUES(%s,MD5(%s),%s)''',(name,email,password
    ))
    mysql.connection.commit()
    cursor.close()
    return "success"

@app.route('/userlogin', methods=['GET', 'POST'])

def userlogin():
    email=request.form.get('email')
    password=request.form.get('password')
    cursor=mysql.connection.cursor()
    cursor.execute(''' SELECT * FROM user WHERE name=%s and password=MD5(%s)''',(email,password))
    row=cursor.fetchone()
    cursor.close()
    if row:
        
            return ("successfully logged")
        
        
    else:
        return "Failed to login"

@app.route('/login', methods=['GET','POST'])
def login():
    return render_template('userlogin.html')

@app.route('/adminlogin', methods=['GET'])
def adminlogin():
    return render_template('adminlogin.html')


@app.route('/admin', methods =['GET', 'POST'])
def admin():
    
    username = request.form.get('uname')
    password = request.form.get('psw')
    print(username, password)
    if username == 'admin' and password == 'ppp':
        #return redirect(url_for('adminlogin', username=username))
        return ('success')
    else:
        return ('Login failed')
    
@app.route('/deptinsert', methods =['GET', 'POST'])
def deptinsert():
    
    dname = request.form.get('dname')
    dloc = request.form.get('dloc')
    sdate = request.form.get('sdate')
    
    date_object = parser.parse(sdate)
    sdate = date_object.astimezone(pytz.timezone('Asia/Kolkata')) 
    #print(date_object)
    cursor = mysql.connection.cursor()
    cursor.execute(''' INSERT INTO dept(dname,dloc,sdate) VALUES(%s,%s,%s)''',(dname,dloc,sdate))
    mysql.connection.commit()
    cursor.close()
    return "Inserted successfully"

@app.route('/rtinsert', methods =['GET', 'POST'])

def rtinsert():
    rtname=request.form.get('rtname')
    rtdes=request.form.get('rtdes')

    cursor=mysql.connection.cursor()
    cursor.execute(''' INSERT INTO resourcetype(rtype,rtdesc) VALUES(%s,%s)''',(rtname,rtdes))
    mysql.connection.commit()
    cursor.close()
    return "Inserted successfully"

@app.route('/rinsert', methods =['GET', 'POST'])
def rinsert():
    
    
    rname = request.form.get('rname')
    rdes=request.form.get('rdes')
    rtid=request.form.get('rrtid')
    ravalue=request.form.get('ravalue')
    rstatus=request.form.get('rstatus')
    f = request.files['rfile']       
    filename = secure_filename(f.filename)
    now = datetime.now()
    dt_string = now.strftime("%d%m%Y%H%M%S")
    rimg=dt_string+"_"+filename
    f.save("static/resources/" + rimg)
    cursor = mysql.connection.cursor()
    cursor.execute(''' INSERT INTO resource(rname,rdesc,rtid,avalue,rimg,rstatus) VALUES(%s,%s,%s,%s,%s,%s)''',(rname,rdes,rtid,ravalue,rimg,rstatus))
    mysql.connection.commit()
    cursor.close()
    return "Inserted successfully"


@app.route('/deptupdate', methods =['GET', 'POST'])
def deptupdate():
    
    did=request.form.get('did')
    dname = request.form.get('dname')
    dloc = request.form.get('dloc')
    sdate = request.form.get('sdate')
    
    date_object = parser.parse(sdate)
    sdate = date_object.astimezone(pytz.timezone('Asia/Kolkata')) 
    #print(date_object)
    cursor = mysql.connection.cursor()
    cursor.execute(''' UPDATE dept SET dname=%s,dloc=%s,sdate=%s WHERE did=%s''',(dname,dloc,sdate,did))
    mysql.connection.commit()
    cursor.close()
    return "Updated successfully"

@app.route('/rtupdate', methods =['GET', 'POST'])
def rtupdate():
    
    rtid=request.form.get('rtid')
    rtype = request.form.get('rtname')
    rtdesc = request.form.get('rtdes')
    
    cursor = mysql.connection.cursor()
    cursor.execute(''' UPDATE resourcetype SET rtype=%s,rtdesc=%s WHERE rtid=%s''',(rtype,rtdesc,rtid))
    mysql.connection.commit()
    cursor.close()
    return "Updated successfully"


@app.route('/deptdelete', methods =['GET', 'POST'])
def deptdelete():
    
    did=request.form.get('did')
    cursor = mysql.connection.cursor()
    cursor.execute(''' DELETE FROM dept WHERE did=%s''',(did,))
    mysql.connection.commit()
    cursor.close()
    return "Deleted successfully"

@app.route('/rtdelete', methods =['GET', 'POST'])
def rtdelete():
    
    rtid=request.form.get('rtid')
    cursor = mysql.connection.cursor()
    cursor.execute(''' DELETE FROM resourcetype WHERE rtid=%s''',(rtid,))
    mysql.connection.commit()
    cursor.close()
    return "Deleted successfully"

@app.route('/rdelete', methods =['GET', 'POST'])
def rdelete():
    
    rid=request.form.get('rid')
    cursor = mysql.connection.cursor()
    cursor.execute(''' DELETE FROM resource WHERE rid=%s''',(rid,))
    mysql.connection.commit()
    cursor.close()
    return "Deleted successfully"

@app.route('/rtnameshow', methods =['GET', 'POST'])

def rtnameshow():
    
    cursor = mysql.connection.cursor()
    cursor.execute("SELECT * FROM resourcetype")
    DBData = cursor.fetchall() 
    cursor.close()
    
    rtnames=''
    for result in DBData:
        print(result)
        rtnames+="<option value="+str(result[0])+">"+result[1]+"</option>"
    return rtnames    
        
           
@app.route('/deptshow', methods =['GET', 'POST'])
def deptshow():
    
    cursor = mysql.connection.cursor()
    cursor.execute("SELECT * FROM dept")
    row_headers=[x[0] for x in cursor.description] 
    DBData = cursor.fetchall() 
    cursor.close()
    json_data=[]
    rstr="<table border><tr>"
    for r in row_headers:
        rstr=rstr+"<th>"+r+"</th>"
    rstr=rstr+"<th>Update</th><th>Delete</th></tr>"
    cnt=0
    did=-1
    for result in DBData:
        cnt=0
        ll=['A','B','C','D','E','F','G','H','I','J','K']
        for row in result:
            if cnt==0:
                did=row
                rstr=rstr+"<td>"+str(row)+"</td>" 
            elif cnt==3:
                rstr=rstr+"<td>"+"<input type=date id="+str(ll[cnt])+str(did)+" value="+str(row)+"></td>"  
            else:
                rstr=rstr+"<td>"+"<input type=text id="+str(ll[cnt])+str(did)+" value=\""+str(row)+"\"></td>"     
            cnt+=1
            
        rstr+="<td><a ><i class=\"fa fa-edit\" aria-hidden=\"true\" onclick=update("+str(did)+")></i></a></td>"
        rstr+="<td><a ><i class=\"fa fa-trash\" aria-hidden=\"true\" onclick=del("+str(did)+")></i></a></td>"
        
        rstr=rstr+"</tr>"
    
    rstr=rstr+"</table>"
    rstr=rstr+'''
    <script type=\"text/javascript\">
    function update(did)
    {
       dname=$("#B"+did).val();
       dloc=$("#C"+did).val();
       sdate=$("#D"+did).val();
       $.ajax({
        url: \"/deptupdate\",
        type: \"POST\",
        data: {did:did,dname:dname,dloc:dloc,sdate:sdate},
        success: function(data){    
        alert(data);
        loaddepartments();
        }
       });
    }
   
    function del(did)
    {
    $.ajax({
        url: \"/deptdelete\",
        type: \"POST\",
        data: {did:did},
        success: function(data){
            alert(data);
            loaddepartments();
        }
        });
    }
    function loaddepartments(){

       $.ajax({
        url: 'http://127.0.0.1:5000/deptshow',
        type: 'POST',
        success: function(data){
          $('#dshow').html(data);
        }
      });
    }
    
    
    </script>

'''
    return rstr


@app.route('/rtshow', methods =['GET', 'POST'])
def rtshow():
    
    cursor = mysql.connection.cursor()

    cursor.execute("SELECT * FROM resourcetype")
    row_headers=[x[0] for x in cursor.description] 
    DBData = cursor.fetchall() 
    cursor.close()
    json_data=[]
    rstr="<table border><tr>"
    for r in row_headers:
        rstr=rstr+"<th>"+r+"</th>"
    rstr=rstr+"<th>Update</th><th>Delete</th></tr>"
    cnt=0
    did=-1
    for result in DBData:
        cnt=0
        ll=['A','B','C','D','E','F','G','H','I','J','K']
        for row in result:
            if cnt==0:
                rtid=row
                rstr=rstr+"<td>"+str(row)+"</td>" 
           
            else:
                rstr=rstr+"<td>"+"<input type=text id="+str(ll[cnt])+str(rtid)+" value=\""+str(row)+"\"></td>"     
            cnt+=1
            
        rstr+="<td><a ><i class=\"fa fa-edit\" aria-hidden=\"true\" onclick=rupdate("+str(rtid)+")></i></a></td>"
        rstr+="<td><a ><i class=\"fa fa-trash\" aria-hidden=\"true\" onclick=rdel("+str(rtid)+")></i></a></td>"
        
        rstr=rstr+"</tr>"
    
    rstr=rstr+"</table>"
    rstr=rstr+'''
    <script type=\"text/javascript\">
    function rupdate(rtid)
    {
       //alert('aha no');
       rtname=$("#B"+rtid).val();
       rtdes=$("#C"+rtid).val();
       $.ajax({
        url: \"/rtupdate\",
        type: \"POST\",
        data: {rtid:rtid,rtname:rtname,rtdes:rtdes},
        success: function(data){
       
        alert(data);
        loadrtypes();
        }
       });
    }
   
    function rdel(rtid)
    {
    $.ajax({
        url: \"/rtdelete\",
        type: \"POST\",
        data: {rtid:rtid},
        success: function(data){
        alert(data);
        loadrtypes();
        }
        });
    }
   
    function loadrtypes(){
       $.ajax({
        url: 'http://127.0.0.1:5000/rtshow',
        type: 'POST',
        success: function(data){
          $('#rtshow').html(data);
        }
      });
    }
    
    
    </script>

'''
    return rstr
@app.route('/rupdate', methods=['GET', 'POST'])

def rupdate():
    rtid=request.form.get('rtid')
    rname=request.form.get('rname')
    rdes=request.form.get('rdes')
    ravalue=request.form.get('ravalue')
    rstatus=request.form.get('rstatus')
    rid=request.form.get('rid')
    cursor = mysql.connection.cursor()
    cursor.execute(''' UPDATE resource SET rname=%s,rdesc=%s,rtid=%s,avalue=%s,rstatus=%s WHERE rid=%s''',(rname,rdes,rtid,ravalue,rstatus,rid))
    mysql.connection.commit()
    cursor.close()
    return "Updated successfully"
@app.route('/rshow', methods=['GET', 'POST'])

def rshow():
    cursor = mysql.connection.cursor()

    cursor.execute("SELECT * FROM resource")
    row_headers=[x[0] for x in cursor.description] 
    DBData = cursor.fetchall() 
    cursor.close()
    json_data=[]
    rstr="<table border><tr>"
    for r in row_headers:
        rstr=rstr+"<th>"+r+"</th>"
    rstr=rstr+"<th>Update</th><th>Delete</th></tr>"
    cnt=0
    did=-1
    for result in DBData:
        cnt=0
        ll=['A','B','C','D','E','F','G','H','I','J','K']
        for row in result:
            if cnt==0:
                rid=row
                rstr=rstr+"<td>"+str(row)+"</td>" 
            elif cnt==5:
                rfil="http://127.0.0.1:5000/static/resources/"+str(row)
                rstr=rstr+"<td>"+"<a href=\""+str(rfil)+"\" target=_blank>File</a></td>"
            else:
                rstr=rstr+"<td>"+"<input type=text id="+str(ll[cnt])+str(rid)+" value=\""+str(row)+"\"></td>"     
            cnt+=1
            
        rstr+="<td><a ><i class=\"fa fa-edit\" aria-hidden=\"true\" onclick=resupdate("+str(rid)+")></i></a></td>"
        rstr+="<td><a ><i class=\"fa fa-trash\" aria-hidden=\"true\" onclick=resdel("+str(rid)+")></i></a></td>"
        
        rstr=rstr+"</tr>"
    
    rstr=rstr+"</table>"
    rstr=rstr+'''
    <script type=\"text/javascript\">
    function resupdate(rid)
    {
       //alert('aha no');

       rname=$("#B"+rid).val();
       rdes=$("#C"+rid).val();
       rtid=$("#D"+rid).val();
       ravalue=$("#E"+rid).val();
       rstatus=$("#G"+rid).val();
       var fd=new FormData();
       fd.append('rname',rname);
       fd.append('rdes',rdes);
       fd.append('rtid',rtid);
       fd.append('ravalue',ravalue);
       fd.append('rstatus',rstatus);
       fd.append('rid',rid); 

       $.ajax({
        url: \"/rupdate\",
        type: \"POST\",
        data: fd,
        processData: false,
        contentType: false,
        success: function(data){
       
        alert(data);
        loadresources();
        }
       });
    }
   
    function resdel(rid)
    {
    $.ajax({
        url: \"/rdelete\",
        type: \"POST\",
        data: {rid:rid},
        success: function(data){
        alert(data);
        loadresources();
        }
        });
    }
   
    
    function loadresources(){
       $.ajax({
        url: 'http://127.0.0.1:5000/rshow',
        type: 'POST',
        success: function(data){
          $('#rshow').html(data);
        }
      });
    }
    
    
    </script>

'''
    return rstr


                              
@app.route('/adminav', methods =['GET', 'POST'])
def adminav():
    return render_template('adminnav.html')


if __name__ == '__main__':
    app.run(debug=True)