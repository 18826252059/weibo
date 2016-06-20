package cn.zhku.modal;

import java.sql.Timestamp;

import javax.persistence.Entity;
import javax.persistence.GeneratedValue;
import javax.persistence.Id;

@Entity
public class Weibo_Message {

	@Id
	@GeneratedValue
	private int Mid;
	
	private String Ucount;
	private String WEI_Ucount;
	private String Mcontent;
	private Timestamp MsendTime;
	
	public Weibo_Message() {
		super();
	}

	public Weibo_Message(String ucount, String wEI_Ucount, String mcontent,
			Timestamp msendTime) {
		super();
		Ucount = ucount;
		WEI_Ucount = wEI_Ucount;
		Mcontent = mcontent;
		MsendTime = msendTime;
	}

	public int getMid() {
		return Mid;
	}

	public void setMid(int mid) {
		Mid = mid;
	}

	public String getUcount() {
		return Ucount;
	}

	public void setUcount(String ucount) {
		Ucount = ucount;
	}

	public String getWEI_Ucount() {
		return WEI_Ucount;
	}

	public void setWEI_Ucount(String wEI_Ucount) {
		WEI_Ucount = wEI_Ucount;
	}

	public String getMcontent() {
		return Mcontent;
	}

	public void setMcontent(String mcontent) {
		Mcontent = mcontent;
	}

	public Timestamp getMsendTime() {
		return MsendTime;
	}

	public void setMsendTime(Timestamp msendTime) {
		MsendTime = msendTime;
	}
}
