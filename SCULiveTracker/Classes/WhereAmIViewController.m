//
//  WhereAmIViewController.m
//  WhereAmI
//
//  Created by Charlie Key on 8/18/09.
//  Copyright Paranoid Ferret Productions 2009. All rights reserved.
//  Modified by An Ming Tan

#import "WhereAmIViewController.h"
#import "JSON/JSON.h"


@implementation WhereAmIViewController

- (void)viewDidLoad {
  [super viewDidLoad];
  locationManager = [[CLLocationManager alloc] init];
  locationManager.delegate = self;
  locationManager.distanceFilter = kCLDistanceFilterNone; // whenever we move
  locationManager.desiredAccuracy = kCLLocationAccuracyHundredMeters; // 100 m
  [locationManager startUpdatingLocation];
	
	NSDate *today = [[NSDate alloc] init];
	NSDateFormatter *sessionDateFormat = [[NSDateFormatter alloc] init];
	[sessionDateFormat setDateFormat:@"yyyy-MM-dd_HH_mm_ss"];
	NSString *sessionTime = [sessionDateFormat stringFromDate:today];
	locInfoSession = [[NSString alloc] initWithFormat:@"AnMing_%@", sessionTime];
	[today release];

}

- (void)locationManager:(CLLocationManager *)manager
    didUpdateToLocation:(CLLocation *)newLocation
           fromLocation:(CLLocation *)oldLocation
{
  double latitude = newLocation.coordinate.latitude;
  NSString *lat = [NSString stringWithFormat:@"%f", 
                   latitude];
  latLabel.text = lat;
  double longitude = newLocation.coordinate.longitude;
  NSString *longt = [NSString stringWithFormat:@"%f", 
                     longitude];
  longLabel.text = longt;
	
	//get current speed
	double speed = newLocation.speed;
	NSString *spd = [NSString stringWithFormat:@"%f", 
                     speed];
	speedLabel.text = spd;
	
	//get current direction
	double course = newLocation.course;
	NSString *crs = [NSString stringWithFormat:@"%f", 
                     course];	
	courseLabel.text = crs;

	//get current timestamp
	NSDate *timestamp = newLocation.timestamp;
	NSDateFormatter *dateFormat = [[NSDateFormatter alloc] init];
	[dateFormat setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
	NSString *time = [dateFormat stringFromDate:timestamp];
	timeLabel.text = time;
	[dateFormat release];
	
	//constructing json string
	requestDict = [NSDictionary dictionaryWithObjectsAndKeys:
								 [NSNumber numberWithDouble:latitude], @"latitude",
								 [NSNumber numberWithDouble:longitude], @"longitude",
								 [NSNumber numberWithDouble:speed], @"speed",
								 [NSNumber numberWithDouble:course], @"course",
								 time, @"timestamp",
								 @"AnMing", @"fbUserName",
								 locInfoSession, @"locInfoSession",
								 nil];
	NSString *jsonString = [requestDict JSONRepresentation];
	NSLog(@"%@", jsonString);
	
	
	NSString *urlString = @"http://184.73.193.2/locInfo/";
	NSURL *url = [[NSURL alloc] initWithString:urlString];
	NSMutableURLRequest *req = [[NSMutableURLRequest alloc] initWithURL:url];
	[req setHTTPMethod:@"POST"];
	[req addValue:@"text/json" forHTTPHeaderField:@"content-type"];
	
	NSData *requestData = [NSData dataWithBytes: [jsonString UTF8String] length: [jsonString length]];
	[req setHTTPBody: requestData];	

	NSHTTPURLResponse* urlResponse = nil;  
	NSError *error = [[NSError alloc] init];  
	NSData *responseData = [NSURLConnection sendSynchronousRequest:req
												 returningResponse:&urlResponse 
															 error:&error];  
	NSString *result = [[NSString alloc] initWithData:responseData
											 encoding:NSUTF8StringEncoding];
	
	NSLog(@"Response Code: %d", [urlResponse statusCode]);
	if ([urlResponse statusCode] >= 200 && [urlResponse statusCode] < 300)
		NSLog(@"Response: %@", result);
	
	statusText.text = result;
	
	[urlString release];
	[url release];
	[req release];
	[result release];	
	
}


- (IBAction)setSwitch:(id)sender;
{
	UISwitch *autoUpdateSwitch = (UISwitch *)sender;
	
	if (autoUpdateSwitch.isOn) {
		[locationManager startUpdatingLocation];
		NSLog(@"startUpdatingLocation");
		NSDate *today = [[NSDate alloc] init];
		NSDateFormatter *sessionDateFormat = [[NSDateFormatter alloc] init];
		[sessionDateFormat setDateFormat:@"yyyy-MM-dd_HH_mm_ss"];
		NSString *sessionTime = [sessionDateFormat stringFromDate:today];
		locInfoSession = [[NSString alloc] initWithFormat:@"AnMing_%@", sessionTime];	
		[today release];
	}
	else {
		[locationManager stopUpdatingLocation];
		NSLog(@"stopUpdatingLocation");
	}

}

- (void)dealloc {
  [locationManager release];
  [super dealloc];
  [locInfoSession release];

}

@end
