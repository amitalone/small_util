package com.poc;

public class ColorBlend {

	public static void main(String[] args) {
		int anchors[] = {0, 50, 75, 100};
		CRGB colors[] = {new CRGB(255, 0, 0), new CRGB(0, 255, 0), new CRGB(0, 0, 255), new CRGB(255, 0, 0)};
		
		for(int i=0; i< 100; i++) {
			CRGB color = getColor(anchors, colors, i);
			System.out.println(color.toCell());
		}
		System.out.println("=============================");
		int anchors2[] = {0, 25, 75, 100, 125};
		CRGB colors2[] = {new CRGB(255, 0, 0), new CRGB(0, 255, 0), new CRGB(0, 0, 255), new CRGB(255, 0, 0), new CRGB(255, 0, 255)};
		
		for(int i=0; i< 125; i++) {
			CRGB color = getColor(anchors2, colors2, i);
			System.out.println(color.toCell());
		}
	}
	
	static CRGB getColor(int[] anchors, CRGB[] colors, int index) {
		int anchorLength = anchors.length;
		if(anchorLength != colors.length) {
			System.out.println("Duh!!");
		}
		int anchorIndex = getAnchorIndex(anchors, index);
		CRGB startcolor = colors[anchorIndex];
		CRGB endColor = colors[anchorIndex + 1];
		int steps = anchors[anchorIndex +1] -anchors[anchorIndex];
		CRGB color = blend(startcolor, endColor, steps , anchors[anchorIndex +1] - index);
		return color;
	}
	static int getAnchorIndex(int[] anchors, int index) {
		for(int i=0; i<anchors.length; i++) {
			if((anchors[i] < index && anchors[i+1] > index) || anchors[i] == index ) {
				return i;
			}
		}
		return -1;
	}
	static CRGB blend(CRGB color1, CRGB color2, float steps, int index) {
		double R = color1.R + (((color2.R - color1.R) / steps) * index);
		double G = color1.G + ((color2.G - color1.G) / steps) * index;
		double B = color1.B + ((color2.B - color1.B) / steps) * index;
		return new CRGB((int)R, (int)G, (int)B);
	}

}

class CRGB {
	public int R=0,G=0,B=0;
	public CRGB(int _r, int _g, int _b) {
		R =_r;
		G = _g;
		B = _b;
	}
	@Override
	public String toString() {
		return "CRGB [R=" + R + ", G=" + G + ", B=" + B + "]";
	}
	
	public String toHex() {
		return String.format("#%02x%02x%02x", R, G, B);
	}
	public String toCell() {
		return " <td style=\"background-color:"+ toHex()+"\">" + "&nbsp;" + "</td>";
	}
}

