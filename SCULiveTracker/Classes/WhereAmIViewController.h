//
//  WhereAmIViewController.h
//  WhereAmI
//
//  Created by Charlie Key on 8/18/09.
//  Copyright Paranoid Ferret Productions 2009. All rights reserved.
//  Modified by An Ming Tan

#import <UIKit/UIKit.h>
#import <CoreLocation/CoreLocation.h>

@interface WhereAmIViewController : UIViewController 
  <CLLocationManagerDelegate>{
  CLLocationManager *locationManager;
  IBOutlet UILabel *latLabel;
  IBOutlet UILabel *longLabel;
  IBOutlet UILabel *speedLabel;	
  IBOutlet UILabel *courseLabel;	
  IBOutlet UILabel *timeLabel;	
  IBOutlet UILabel *statusText;
  NSDictionary *requestDict;
  NSString *locInfoSession;
}

- (IBAction)setSwitch:(id)sender;

@end

