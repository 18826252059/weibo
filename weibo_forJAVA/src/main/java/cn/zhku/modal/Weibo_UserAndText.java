package cn.zhku.modal;

import java.sql.Timestamp;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

@Entity
public class Weibo_UserAndText {

	@Id
	@GeneratedValue
	private int UTid;
	
	private String Ucount;
	private int Tid;
	private int oprationType;
	private Timestamp actTime;
	public Weibo_UserAndText() {
		super();
		// TODO Auto-generated constructor stub
	}
	public Weibo_UserAndText(String ucount, int tid, int oprationType,
			Timestamp actTime) {
		super();
		Ucount = ucount;
		Tid = tid;
		this.oprationType = oprationType;
		this.actTime = actTime;
	}
	public int getUTid() {
		return UTid;
	}
	public void setUTid(int uTid) {
		UTid = uTid;
	}
	public String getUcount() {
		return Ucount;
	}
	public void setUcount(String ucount) {
		Ucount = ucount;
	}
	public int getTid() {
		return Tid;
	}
	public void setTid(int tid) {
		Tid = tid;
	}
	public int getOprationType() {
		return oprationType;
	}
	public void setOprationType(int oprationType) {
		this.oprationType = oprationType;
	}
	public Timestamp getActTime() {
		return actTime;
	}
	public void setActTime(Timestamp actTime) {
		this.actTime = actTime;
	}
	
	
}
