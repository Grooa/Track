import { render } from 'enzyme';

import VideoCard from '../VideoCard';

describe('<VideoCard />', () => {
  let video;

  beforeEach(() => {
    video = {
      title: 'Test title',
      shortDescription: 'hello world',
      url: 'https://test.com/test',
    };
  });

  it('should render properly', () => {
    const wrapper = render(<VideoCard video={video} number={1}/>);

    expect(wrapper).toMatchSnapshot();
  });

  it('should render disabled properly', () => {
    video.url = null;

    const wrapper = render(<VideoCard video={video} number={1}/>);

    expect(wrapper).toMatchSnapshot();
  });
});
