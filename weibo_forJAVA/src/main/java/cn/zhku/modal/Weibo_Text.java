package cn.zhku.modal;

import java.sql.Timestamp;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

@Entity
public class Weibo_Text {
	
	@Id
	@GeneratedValue
	private int Tid;
	
	private String Ucount;
	private String content;
	private Timestamp sendTime;
	public Weibo_Text() {
		super();
		// TODO Auto-generated constructor stub
	}
	public Weibo_Text(String ucount, String content, Timestamp sendTime) {
		super();
		Ucount = ucount;
		this.content = content;
		this.sendTime = sendTime;
	}
	public int getTid() {
		return Tid;
	}
	public void setTid(int tid) {
		Tid = tid;
	}
	public String getUcount() {
		return Ucount;
	}
	public void setUcount(String ucount) {
		Ucount = ucount;
	}
	public String getContent() {
		return content;
	}
	public void setContent(String content) {
		this.content = content;
	}
	public Timestamp getSendTime() {
		return sendTime;
	}
	public void setSendTime(Timestamp sendTime) {
		this.sendTime = sendTime;
	}
	
	
}
