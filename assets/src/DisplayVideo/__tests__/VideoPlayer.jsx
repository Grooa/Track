import { render } from 'enzyme';

import VideoPlayer from '../VideoPlayer';

describe('<VideoPlayer />', () => {
  it('should render placeholder properly', () => {
    const wrapper = render(<VideoPlayer />);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render video-container properly', () => {
    const wrapper = render(<VideoPlayer
      url="/somepath/some-video.mp4"
      poster="somepath/some-poster.jpg"/>);

    expect(wrapper).toMatchSnapshot();
  });
});
