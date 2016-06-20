package cn.zhku.modal;

import java.sql.Date;
import java.sql.Timestamp;

import javax.persistence.Entity;
import javax.persistence.Id;

@Entity
public class Weibo_User {
	
	@Id
	private String Ucount;
	
	private String Upsw;
	private String Unick;
	private String Uphoto;
	private String Uspan;
	private String Usex;
	private Date Ubirthday;
	private String Uemail;
	private String Uqq;
	private Timestamp UregisterTime;
	private String Uphone;
	
	public Weibo_User() {
		super();
	}

	public Weibo_User(String ucount, String upsw, String unick, String uphoto,
			String uspan, String usex, Date ubirthday, String uemail,
			String uqq, Timestamp uregisterTime, String uphone) {
		super();
		Ucount = ucount;
		Upsw = upsw;
		Unick = unick;
		Uphoto = uphoto;
		Uspan = uspan;
		Usex = usex;
		Ubirthday = ubirthday;
		Uemail = uemail;
		Uqq = uqq;
		UregisterTime = uregisterTime;
		Uphone = uphone;
	}

	public String getUcount() {
		return Ucount;
	}

	public void setUcount(String ucount) {
		Ucount = ucount;
	}

	public String getUpsw() {
		return Upsw;
	}

	public void setUpsw(String upsw) {
		Upsw = upsw;
	}

	public String getUnick() {
		return Unick;
	}

	public void setUnick(String unick) {
		Unick = unick;
	}

	public String getUphoto() {
		return Uphoto;
	}

	public void setUphoto(String uphoto) {
		Uphoto = uphoto;
	}

	public String getUspan() {
		return Uspan;
	}

	public void setUspan(String uspan) {
		Uspan = uspan;
	}

	public String getUsex() {
		return Usex;
	}

	public void setUsex(String usex) {
		Usex = usex;
	}

	public Date getUbirthday() {
		return Ubirthday;
	}

	public void setUbirthday(Date ubirthday) {
		Ubirthday = ubirthday;
	}

	public String getUemail() {
		return Uemail;
	}

	public void setUemail(String uemail) {
		Uemail = uemail;
	}

	public String getUqq() {
		return Uqq;
	}

	public void setUqq(String uqq) {
		Uqq = uqq;
	}

	public Timestamp getUregisterTime() {
		return UregisterTime;
	}

	public void setUregisterTime(Timestamp uregisterTime) {
		UregisterTime = uregisterTime;
	}

	public String getUphone() {
		return Uphone;
	}

	public void setUphone(String uphone) {
		Uphone = uphone;
	}
}
